<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629181021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE edition_artist (editions_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_E2C0229F6BD6E9CC (editions_id), INDEX IDX_E2C0229FB7970CF8 (artist_id), PRIMARY KEY(editions_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229F6BD6E9CC FOREIGN KEY (editions_id) REFERENCES editions (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist ADD CONSTRAINT FK_E2C0229FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229F6BD6E9CC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE edition_artist DROP FOREIGN KEY FK_E2C0229FB7970CF8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE edition_artist
        SQL);
    }
}
