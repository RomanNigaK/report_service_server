<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324071031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_items DROP FOREIGN KEY FK_CB02EE34A76ED395');
        $this->addSql('DROP INDEX IDX_CB02EE34A76ED395 ON report_items');
        $this->addSql('ALTER TABLE report_items ADD createAt DATETIME NOT NULL, ADD updateAt DATETIME NOT NULL, DROP user_id, DROP create_at, DROP update_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_items ADD user_id VARCHAR(36) DEFAULT NULL, ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP createAt, DROP updateAt');
        $this->addSql('ALTER TABLE report_items ADD CONSTRAINT FK_CB02EE34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CB02EE34A76ED395 ON report_items (user_id)');
    }
}
