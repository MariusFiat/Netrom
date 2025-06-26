<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626211537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD artist_id_id INT NOT NULL, ADD stage_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB1F48AE04 FOREIGN KEY (artist_id_id) REFERENCES artist (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBFFE32C93 FOREIGN KEY (stage_id_id) REFERENCES stage (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A3811FB1F48AE04 ON schedule (artist_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A3811FBFFE32C93 ON schedule (stage_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB1F48AE04
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBFFE32C93
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5A3811FB1F48AE04 ON schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5A3811FBFFE32C93 ON schedule
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP artist_id_id, DROP stage_id_id
        SQL);
    }
}
