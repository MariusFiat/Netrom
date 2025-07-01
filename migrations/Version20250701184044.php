<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701184044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP INDEX UNIQ_6117D13BE7DB8BF4, ADD INDEX IDX_6117D13BE7DB8BF4 (ticket_type_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP INDEX IDX_6117D13BE7DB8BF4, ADD UNIQUE INDEX UNIQ_6117D13BE7DB8BF4 (ticket_type_id_id)
        SQL);
    }
}
