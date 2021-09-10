<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910085430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet ADD adherent_woman_id INT NOT NULL, ADD adherent_man_id INT NOT NULL');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CEE60D7A92 FOREIGN KEY (adherent_woman_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CEF701299A FOREIGN KEY (adherent_man_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_E9F6D3CEE60D7A92 ON meet (adherent_woman_id)');
        $this->addSql('CREATE INDEX IDX_E9F6D3CEF701299A ON meet (adherent_man_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CEE60D7A92');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CEF701299A');
        $this->addSql('DROP INDEX IDX_E9F6D3CEE60D7A92 ON meet');
        $this->addSql('DROP INDEX IDX_E9F6D3CEF701299A ON meet');
        $this->addSql('ALTER TABLE meet DROP adherent_woman_id, DROP adherent_man_id');
    }
}
