<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206151926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts_payment_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_training_contract (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, sector VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diploma (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, module_id INT NOT NULL, grade DOUBLE PRECISION NOT NULL, INDEX IDX_595AAE34CB944F1A (student_id), INDEX IDX_595AAE34AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_31FC43DDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructor_module (instructor_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_739CF9748C4FC193 (instructor_id), INDEX IDX_739CF974AFC2B591 (module_id), PRIMARY KEY(instructor_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructor_section (instructor_id INT NOT NULL, section_id INT NOT NULL, INDEX IDX_4FD1DFEF8C4FC193 (instructor_id), INDEX IDX_4FD1DFEFD823E37A (section_id), PRIMARY KEY(instructor_id, section_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, speciality TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C242628EA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, campus_id INT NOT NULL, year INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_2D737AEFAF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_426EF392A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff_campus (staff_id INT NOT NULL, campus_id INT NOT NULL, INDEX IDX_9874DAAAD4D57CD (staff_id), INDEX IDX_9874DAAAAF5D55E1 (campus_id), PRIMARY KEY(staff_id, campus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, campus_id INT NOT NULL, level_id INT NOT NULL, entry_level_id INT NOT NULL, exit_level_id INT DEFAULT NULL, accounts_payment_type_id INT NOT NULL, company_training_contract_id INT DEFAULT NULL, last_diploma_id INT NOT NULL, gender_id INT NOT NULL, region_id INT NOT NULL, entry_year INT NOT NULL, exit_year INT DEFAULT NULL, professional_training_contract TINYINT(1) NOT NULL, accounts_paid TINYINT(1) NOT NULL, accounts_payment_due DOUBLE PRECISION NOT NULL, accounts_reminded TINYINT(1) NOT NULL, date_start_contract DATE DEFAULT NULL, employed_as VARCHAR(255) DEFAULT NULL, number_of_absences INT NOT NULL, date_of_birth DATE NOT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF33AF5D55E1 (campus_id), INDEX IDX_B723AF335FB14BA7 (level_id), INDEX IDX_B723AF3331F6ABEF (entry_level_id), INDEX IDX_B723AF337DAD4FF3 (exit_level_id), INDEX IDX_B723AF337C8383EA (accounts_payment_type_id), INDEX IDX_B723AF33DC2EECBC (company_training_contract_id), INDEX IDX_B723AF3340EEA45F (last_diploma_id), INDEX IDX_B723AF33708A0E0 (gender_id), INDEX IDX_B723AF3398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_module (student_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_8212FEA8CB944F1A (student_id), INDEX IDX_8212FEA8AFC2B591 (module_id), PRIMARY KEY(student_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instructor_module ADD CONSTRAINT FK_739CF9748C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor_module ADD CONSTRAINT FK_739CF974AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor_section ADD CONSTRAINT FK_4FD1DFEF8C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor_section ADD CONSTRAINT FK_4FD1DFEFD823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE staff_campus ADD CONSTRAINT FK_9874DAAAD4D57CD FOREIGN KEY (staff_id) REFERENCES staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff_campus ADD CONSTRAINT FK_9874DAAAAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF335FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3331F6ABEF FOREIGN KEY (entry_level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337DAD4FF3 FOREIGN KEY (exit_level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF337C8383EA FOREIGN KEY (accounts_payment_type_id) REFERENCES accounts_payment_type (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33DC2EECBC FOREIGN KEY (company_training_contract_id) REFERENCES company_training_contract (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3340EEA45F FOREIGN KEY (last_diploma_id) REFERENCES diploma (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE student_module ADD CONSTRAINT FK_8212FEA8CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_module ADD CONSTRAINT FK_8212FEA8AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337C8383EA');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFAF5D55E1');
        $this->addSql('ALTER TABLE staff_campus DROP FOREIGN KEY FK_9874DAAAAF5D55E1');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33AF5D55E1');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33DC2EECBC');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3340EEA45F');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33708A0E0');
        $this->addSql('ALTER TABLE instructor_module DROP FOREIGN KEY FK_739CF9748C4FC193');
        $this->addSql('ALTER TABLE instructor_section DROP FOREIGN KEY FK_4FD1DFEF8C4FC193');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF335FB14BA7');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3331F6ABEF');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF337DAD4FF3');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34AFC2B591');
        $this->addSql('ALTER TABLE instructor_module DROP FOREIGN KEY FK_739CF974AFC2B591');
        $this->addSql('ALTER TABLE student_module DROP FOREIGN KEY FK_8212FEA8AFC2B591');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3398260155');
        $this->addSql('ALTER TABLE instructor_section DROP FOREIGN KEY FK_4FD1DFEFD823E37A');
        $this->addSql('ALTER TABLE staff_campus DROP FOREIGN KEY FK_9874DAAAD4D57CD');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE student_module DROP FOREIGN KEY FK_8212FEA8CB944F1A');
        $this->addSql('DROP TABLE accounts_payment_type');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE company_training_contract');
        $this->addSql('DROP TABLE diploma');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE instructor');
        $this->addSql('DROP TABLE instructor_module');
        $this->addSql('DROP TABLE instructor_section');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE staff_campus');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_module');
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name');
    }
}
