<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908081751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD status_matrimoniale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06087D0EECC FOREIGN KEY (status_matrimoniale_id) REFERENCES adherent_option (id)');
        $this->addSql('CREATE INDEX IDX_90D3F06087D0EECC ON adherent (status_matrimoniale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06087D0EECC');
        $this->addSql('DROP INDEX IDX_90D3F06087D0EECC ON adherent');
        $this->addSql('ALTER TABLE adherent DROP status_matrimoniale_id');
    }
}
