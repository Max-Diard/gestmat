<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916095933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence_agence (agence_source INT NOT NULL, agence_target INT NOT NULL, INDEX IDX_930D7F8DA8469A57 (agence_source), INDEX IDX_930D7F8DB1A3CAD8 (agence_target), PRIMARY KEY(agence_source, agence_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agence_agence ADD CONSTRAINT FK_930D7F8DA8469A57 FOREIGN KEY (agence_source) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agence_agence ADD CONSTRAINT FK_930D7F8DB1A3CAD8 FOREIGN KEY (agence_target) REFERENCES agence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agence_agence');
    }
}
