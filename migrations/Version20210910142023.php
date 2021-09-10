<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910142023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD type_payment_id INT NOT NULL, ADD contract_date DATE NOT NULL, ADD contract_started_at DATE NOT NULL, ADD contrat_ending_at DATE NOT NULL, ADD contract_ammount INT NOT NULL, ADD contrat_comments LONGTEXT DEFAULT NULL, CHANGE link_contrat link_contract LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06019C0759E FOREIGN KEY (type_payment_id) REFERENCES adherent_option (id)');
        $this->addSql('CREATE INDEX IDX_90D3F06019C0759E ON adherent (type_payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06019C0759E');
        $this->addSql('DROP INDEX IDX_90D3F06019C0759E ON adherent');
        $this->addSql('ALTER TABLE adherent ADD link_contrat LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_payment_id, DROP link_contract, DROP contract_date, DROP contract_started_at, DROP contrat_ending_at, DROP contract_ammount, DROP contrat_comments');
    }
}
