<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309133352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD company_hired_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF335AE950BD FOREIGN KEY (company_hired_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_B723AF335AE950BD ON student (company_hired_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF335AE950BD');
        $this->addSql('DROP INDEX IDX_B723AF335AE950BD ON student');
        $this->addSql('ALTER TABLE student DROP company_hired_id');
    }
}
