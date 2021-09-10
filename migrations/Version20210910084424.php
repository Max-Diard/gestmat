<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910084424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meet (id INT AUTO_INCREMENT NOT NULL, status_meet_woman_id INT DEFAULT NULL, status_meet_man_id INT DEFAULT NULL, started_at DATE NOT NULL, return_at_woman DATE DEFAULT NULL, return_at_man DATE DEFAULT NULL, comments_woman LONGTEXT DEFAULT NULL, comments_man LONGTEXT DEFAULT NULL, INDEX IDX_E9F6D3CE21F25DFE (status_meet_woman_id), INDEX IDX_E9F6D3CE7A61CCA2 (status_meet_man_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE21F25DFE FOREIGN KEY (status_meet_woman_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE7A61CCA2 FOREIGN KEY (status_meet_man_id) REFERENCES adherent_option (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE meet');
    }
}
