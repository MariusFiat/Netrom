<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626205918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD editions_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB1EF8953B FOREIGN KEY (editions_id_id) REFERENCES editions (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A3811FB1EF8953B ON schedule (editions_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB1EF8953B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5A3811FB1EF8953B ON schedule
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP editions_id_id
        SQL);
    }
}
