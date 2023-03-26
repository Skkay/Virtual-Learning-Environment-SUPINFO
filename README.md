# Virtual Learning Environment SUPINFO
School project for SUPINFO.

## Context

> The academic management of SUPINFO wishes to create a tool that will centralize all the information concerning a student. It should be possible to have a complete summary of the pedagogical, administrative and professional information.


## Run

### Using Docker

1. Create `.env` file from `.env.dist` and update with your values
```
cp .env.dist .env
```

2. Build the Docker images: 
```
docker compose build --no-cache --pull
```

3. Start
```
docker compose up -d
```

4. Browse `https://localhost:8443` (the port `8443` is defined in `docker-compose.override.yml`)


### Using Symfony CLI

1. Create `.env` file with:
```
APP_ENV=dev # or prod
APP_SECRET=YourRandom32CharactersAppSecret!

DATABASE_URL=mysql://user:password@127.0.0.1:3306/app?serverVersion=mariadb-10.7.4

MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
MAILER_DSN=smtp://localhost:25
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=admin
```

2. Install PHP packages
```
compose install
```

3. Install Node packages
```
yarn install
```

4. Build with Webpack
```
yarn encore dev # or prod
```

5. Create database and run migrations
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Create required directories (until these issues are closed: [#33](https://github.com/Skkay/4PROJ/issues/33) [#34](https://github.com/Skkay/4PROJ/issues/34))
```
mkdir -p public/etl_data
mkdir -p public/export
```

7. Start
```
symfony server:start
```

8. Consume message
```
php bin/console messenger:consume asyncETL
```
This step can also be done using Supervisor. Refer to [Symfony documentation](https://symfony.com/doc/5.4/messenger.html#supervisor-configuration). 

An example of Supervisor configuration can be found in `./docker/php/supervisor`.


## ETL: Import data

### Import your first data

The folowing steps describe an importation example with the `./data_sample/first/Planning.csv` data file and `./data_sample/first/Plannig.json` schema.

1. Log in to the admin account (defined in `.env` file)
2. In the dashboard, click on *Data schemas*
3. Click on *Create* button
    - Set label as `Planning.csv`
    - Copy / paste JSON schema (`./data_sample/first/Plannig.json`)
    - Click on *Create* button
4. In the dashboard, click on *Data files*
5. Select and upload `./data_sample/first/Planning.csv` file
6. Click on *Start importation* button and wait until the overall progress bar reaches 100%
7. To verify that the import was successful, return to the dashboard and click on *Planning events*


### Mock data

A mock data set are present in `./data_sample/data/csv` with their JSON schema in `./data_sample/data/schemas`.

An SQL script is also available to add all JSON schemas at once: `./data_sample/data/schemas/schemas.sql`.

If you import this data set, then you can log in to these accounts to test different roles:
- `templegirdlestone@supinfo.com` → ACADEMIC_DIRECTOR
- `ritamatthai@supinfo.com` → EDUCATIONAL_COORDINATOR
- `ashbeyfaber@supinfo.com` → ACCOUNTS
- `kesleybenedek@supinfo.com` → STUDENT

These accounts don't have a password, so you must use the login link method. 


## Common issues

### The login link is displayed in the browser and is not sent by email
The email sending feature is not yet developed, so the login link is returned directly to the browser.

### Login link not working
> HTTP 404 Not Found: No route found for "GET https://localhost:8443//login_check"

or

> Invalid or expired login link.

The JSON response that contains the login URL may be encoded. Be sure to replace the encoded characters: `\u0026` to `&` and `\/` to `/`.

Example:
```
{"login_link":"https:\/\/localhost:8443\/login_check?user=ringblazy@supinfo.com\u0026expires=1654516524\u0026hash=ODFkNDJjMDg0ZTI1NWU1Y2E3Y2Q0NWNlMDc5MGNmZTJiNmU4NmM0YWRiYzVkZTgyNzAxOGFhNjg2N2M2NTQ3Yg%3D%3D"}
```
to
```json
{
    "login_link": "https://localhost:8443/login_check?user=ringblazy@supinfo.com&expires=1654516524&hash=ODFkNDJjMDg0ZTI1NWU1Y2E3Y2Q0NWNlMDc5MGNmZTJiNmU4NmM0YWRiYzVkZTgyNzAxOGFhNjg2N2M2NTQ3Yg%3D%3D"
}
```

### Importation not working, the progress bars are stuck at 0%
Be sure that the `asyncETL` message are consumed or restart them
```
php bin/console messenger:consume asyncETL
```

If it still doesn't work, restart `asyncETL` with `-vv` or `-vvv` option to get more details.
```
php bin/console messenger:consume asyncETL -vvv
```
