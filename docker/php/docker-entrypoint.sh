#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	mkdir -p var/cache var/log

	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-interaction
        yarn encore dev
    else
        composer install --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader
        composer run-script --no-dev post-install-cmd
        yarn encore prod
	fi

    chmod 777 bin/console

    echo "Waiting for database to be ready..."
    ATTEMPTS_LEFT_TO_REACH_DATABASE=60
    until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(bin/console dbal:run-sql "SELECT 1" 2>&1); do
        if [ $? -eq 255 ]; then
            # If the Doctrine command exits with 255, an unrecoverable error occurred
            ATTEMPTS_LEFT_TO_REACH_DATABASE=0
            break
        fi
        sleep 1
        ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
        echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left"
    done

    if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
        echo "The database is not up or not reachable:"
        echo "$DATABASE_ERROR"
        exit 1
    else
        echo "The database is now ready and reachable"
    fi

    if [ "$( find ./migrations -iname '*.php' -print -quit )" ]; then
        bin/console doctrine:migrations:migrate --no-interaction
    fi

    bin/console app:create-default-admin-user

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX public
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX public

    echo "Starting supervisord"
    mkdir -p /var/log/supervisor
    supervisord -c /etc/supervisor/supervisord.conf

    echo "Creating required directories"
    mkdir -p public/etl_data
    mkdir -p public/accounts
    mkdir -p public/export
fi

exec docker-php-entrypoint "$@"
