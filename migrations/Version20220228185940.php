<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228185940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8FB177B2EA750E8 ON accounts_payment_type (label)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5277AA365E237E06 ON company_training_contract (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC218957EA750E8 ON diploma (label)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7470A42EA750E8 ON gender (label)');
        $this->addSql('ALTER TABLE grade CHANGE student_id student_id INT DEFAULT NULL, CHANGE module_id module_id INT DEFAULT NULL, CHANGE grade grade DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9AEACC13EA750E8 ON level (label)');
        $this->addSql('ALTER TABLE module CHANGE speciality speciality TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F62F1765E237E06 ON region (name)');
        $this->addSql('ALTER TABLE section CHANGE campus_id campus_id INT DEFAULT NULL, CHANGE year year INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEFEA750E8 ON section (label)');
        $this->addSql('ALTER TABLE staff CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student CHANGE user_id user_id INT DEFAULT NULL, CHANGE campus_id campus_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE entry_level_id entry_level_id INT DEFAULT NULL, CHANGE accounts_payment_type_id accounts_payment_type_id INT DEFAULT NULL, CHANGE last_diploma_id last_diploma_id INT DEFAULT NULL, CHANGE gender_id gender_id INT DEFAULT NULL, CHANGE region_id region_id INT DEFAULT NULL, CHANGE entry_year entry_year INT DEFAULT NULL, CHANGE professional_training_contract professional_training_contract TINYINT(1) DEFAULT NULL, CHANGE accounts_paid accounts_paid TINYINT(1) DEFAULT NULL, CHANGE accounts_payment_due accounts_payment_due DOUBLE PRECISION DEFAULT NULL, CHANGE accounts_reminded accounts_reminded TINYINT(1) DEFAULT NULL, CHANGE number_of_absences number_of_absences INT DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8FB177B2EA750E8 ON accounts_payment_type');
        $this->addSql('DROP INDEX UNIQ_5277AA365E237E06 ON company_training_contract');
        $this->addSql('DROP INDEX UNIQ_EC218957EA750E8 ON diploma');
        $this->addSql('DROP INDEX UNIQ_C7470A42EA750E8 ON gender');
        $this->addSql('ALTER TABLE grade CHANGE student_id student_id INT NOT NULL, CHANGE module_id module_id INT NOT NULL, CHANGE grade grade DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9AEACC13EA750E8 ON level');
        $this->addSql('ALTER TABLE module CHANGE speciality speciality TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_F62F1765E237E06 ON region');
        $this->addSql('DROP INDEX UNIQ_2D737AEFEA750E8 ON section');
        $this->addSql('ALTER TABLE section CHANGE campus_id campus_id INT NOT NULL, CHANGE year year INT NOT NULL');
        $this->addSql('ALTER TABLE staff CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE student CHANGE user_id user_id INT NOT NULL, CHANGE campus_id campus_id INT NOT NULL, CHANGE level_id level_id INT NOT NULL, CHANGE entry_level_id entry_level_id INT NOT NULL, CHANGE accounts_payment_type_id accounts_payment_type_id INT NOT NULL, CHANGE last_diploma_id last_diploma_id INT NOT NULL, CHANGE gender_id gender_id INT NOT NULL, CHANGE region_id region_id INT NOT NULL, CHANGE entry_year entry_year INT NOT NULL, CHANGE professional_training_contract professional_training_contract TINYINT(1) NOT NULL, CHANGE accounts_paid accounts_paid TINYINT(1) NOT NULL, CHANGE accounts_payment_due accounts_payment_due DOUBLE PRECISION NOT NULL, CHANGE accounts_reminded accounts_reminded TINYINT(1) NOT NULL, CHANGE number_of_absences number_of_absences INT NOT NULL, CHANGE date_of_birth date_of_birth DATE NOT NULL, CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
