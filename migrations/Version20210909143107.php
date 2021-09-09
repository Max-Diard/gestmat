<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909143107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD owner_id INT DEFAULT NULL, DROP owner, CHANGE link_picture link_picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0607E3C61F9 FOREIGN KEY (owner_id) REFERENCES adherent_option (id)');
        $this->addSql('CREATE INDEX IDX_90D3F0607E3C61F9 ON adherent (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0607E3C61F9');
        $this->addSql('DROP INDEX IDX_90D3F0607E3C61F9 ON adherent');
        $this->addSql('ALTER TABLE adherent ADD owner TINYINT(1) NOT NULL, DROP owner_id, CHANGE link_picture link_picture LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
