<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426170954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE staff_campus');
        $this->addSql('ALTER TABLE staff ADD campus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_426EF392AF5D55E1 ON staff (campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE staff_campus (staff_id INT NOT NULL, campus_id INT NOT NULL, INDEX IDX_9874DAAAD4D57CD (staff_id), INDEX IDX_9874DAAAAF5D55E1 (campus_id), PRIMARY KEY(staff_id, campus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE staff_campus ADD CONSTRAINT FK_9874DAAAD4D57CD FOREIGN KEY (staff_id) REFERENCES staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff_campus ADD CONSTRAINT FK_9874DAAAAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF392AF5D55E1');
        $this->addSql('DROP INDEX IDX_426EF392AF5D55E1 ON staff');
        $this->addSql('ALTER TABLE staff DROP campus_id');
    }
}
