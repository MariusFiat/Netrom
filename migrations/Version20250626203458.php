<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626203458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD user_id_id INT NOT NULL, ADD festival_id_id INT NOT NULL, ADD ticket_type_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B11F56659 FOREIGN KEY (festival_id_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BE7DB8BF4 FOREIGN KEY (ticket_type_id_id) REFERENCES ticket (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6117D13B9D86650F ON purchase (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6117D13B11F56659 ON purchase (festival_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_6117D13BE7DB8BF4 ON purchase (ticket_type_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B9D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B11F56659
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BE7DB8BF4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6117D13B9D86650F ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6117D13B11F56659 ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_6117D13BE7DB8BF4 ON purchase
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchase DROP user_id_id, DROP festival_id_id, DROP ticket_type_id_id
        SQL);
    }
}
