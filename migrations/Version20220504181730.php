<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503185523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planning_event (id INT NOT NULL, campus_id INT DEFAULT NULL, level_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(2048) DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_AA02B038AF5D55E1 (campus_id), INDEX IDX_AA02B0385FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B038AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B0385FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE planning_event');
    }
}
