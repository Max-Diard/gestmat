<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910082059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859615593E9');
        $this->addSql('DROP INDEX IDX_E98F2859615593E9 ON contract');
        $this->addSql('ALTER TABLE contract CHANGE type_paiement_id type_payment_id INT NOT NULL');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F285919C0759E FOREIGN KEY (type_payment_id) REFERENCES adherent_option (id)');
        $this->addSql('CREATE INDEX IDX_E98F285919C0759E ON contract (type_payment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F285919C0759E');
        $this->addSql('DROP INDEX IDX_E98F285919C0759E ON contract');
        $this->addSql('ALTER TABLE contract CHANGE type_payment_id type_paiement_id INT NOT NULL');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859615593E9 FOREIGN KEY (type_paiement_id) REFERENCES adherent_option (id)');
        $this->addSql('CREATE INDEX IDX_E98F2859615593E9 ON contract (type_paiement_id)');
    }
}
