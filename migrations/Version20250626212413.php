<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626212413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD edition_id_id INT NOT NULL, ADD artist_id_id INT NOT NULL, ADD stage_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A7885FB94DF FOREIGN KEY (edition_id_id) REFERENCES editions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A781F48AE04 FOREIGN KEY (artist_id_id) REFERENCES artist (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A78FFE32C93 FOREIGN KEY (stage_id_id) REFERENCES stage (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A7885FB94DF ON festival_artist (edition_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A781F48AE04 ON festival_artist (artist_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A78FFE32C93 ON festival_artist (stage_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A7885FB94DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A781F48AE04
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A78FFE32C93
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A7885FB94DF ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A781F48AE04 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A78FFE32C93 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP edition_id_id, DROP artist_id_id, DROP stage_id_id
        SQL);
    }
}
