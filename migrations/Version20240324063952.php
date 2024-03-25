<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324063952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id VARCHAR(36) NOT NULL, name VARCHAR(100) DEFAULT NULL, dateCreate DATETIME NOT NULL, dateUpdate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) DEFAULT NULL, company_id VARCHAR(36) DEFAULT NULL, createAt DATETIME NOT NULL, updateAt DATETIME NOT NULL, ordering INT NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F7784979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_items (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) DEFAULT NULL, report_id VARCHAR(36) DEFAULT NULL, sum INT NOT NULL, address VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CB02EE34A76ED395 (user_id), INDEX IDX_CB02EE344BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id VARCHAR(36) NOT NULL, company_id VARCHAR(36) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, sername VARCHAR(100) NOT NULL, patronymic VARCHAR(100) NOT NULL, phone VARCHAR(15) NOT NULL, dateCreated DATETIME NOT NULL, dateUpdate DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE report_items ADD CONSTRAINT FK_CB02EE34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_items ADD CONSTRAINT FK_CB02EE344BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784979B1AD6');
        $this->addSql('ALTER TABLE report_items DROP FOREIGN KEY FK_CB02EE34A76ED395');
        $this->addSql('ALTER TABLE report_items DROP FOREIGN KEY FK_CB02EE344BD2A4C0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_items');
        $this->addSql('DROP TABLE user');
    }
}
