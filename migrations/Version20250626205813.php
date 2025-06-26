<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626205813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions ADD festival_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editions ADD CONSTRAINT FK_96AE53E611F56659 FOREIGN KEY (festival_id_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_96AE53E611F56659 ON editions (festival_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP edition_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE editions DROP FOREIGN KEY FK_96AE53E611F56659
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_96AE53E611F56659 ON editions
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE editions DROP festival_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD edition_id INT NOT NULL
        SQL);
    }
}
