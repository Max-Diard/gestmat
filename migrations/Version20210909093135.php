<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909093135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, status_matrimoniale_id INT DEFAULT NULL, status_meet_id INT DEFAULT NULL, instruction_id INT DEFAULT NULL, lodging_id INT DEFAULT NULL, smoking_id INT DEFAULT NULL, hair_id INT DEFAULT NULL, zodiaque_id INT DEFAULT NULL, eyes_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, preference_contact_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, search_instruction_id INT DEFAULT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, birthdate DATE NOT NULL, comments1 LONGTEXT DEFAULT NULL, comments2 LONGTEXT DEFAULT NULL, comments3 LONGTEXT DEFAULT NULL, phone_mobile VARCHAR(255) NOT NULL, phone_home VARCHAR(255) DEFAULT NULL, phone_work VARCHAR(255) DEFAULT NULL, phone_comments VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) NOT NULL, size NUMERIC(2,2) NOT NULL, weight NUMERIC(3,0) NOT NULL, permis TINYINT(1) NOT NULL, car TINYINT(1) NOT NULL, announcement_publish TINYINT(1) NOT NULL, announcement_presentation LONGTEXT NOT NULL, announcement_free LONGTEXT DEFAULT NULL, announcement_date_free DATE DEFAULT NULL, announcement_newspaper LONGTEXT DEFAULT NULL, announcement_date_newspaper DATE DEFAULT NULL, owner TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, link_picture LONGTEXT DEFAULT NULL, link_contrat LONGTEXT DEFAULT NULL, link_information LONGTEXT DEFAULT NULL, link_picture_announcement LONGTEXT DEFAULT NULL, address_street VARCHAR(255) NOT NULL, address_street2 VARCHAR(255) DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, child_girl INT NOT NULL, child_boy INT NOT NULL, child_dependent_girl INT NOT NULL, child_dependent_boy INT NOT NULL, search_age_min INT NOT NULL, search_age_max INT NOT NULL, search_single TINYINT(1) NOT NULL, search_divorced TINYINT(1) NOT NULL, search_windower TINYINT(1) NOT NULL, search_profession VARCHAR(255) DEFAULT NULL, search_region VARCHAR(255) DEFAULT NULL, search_more LONGTEXT DEFAULT NULL, search_accept_children TINYINT(1) NOT NULL, search_number_accept_children INT NOT NULL, INDEX IDX_90D3F06087D0EECC (status_matrimoniale_id), INDEX IDX_90D3F060B6BC8E3 (status_meet_id), INDEX IDX_90D3F06062A10F76 (instruction_id), INDEX IDX_90D3F06087335AF1 (lodging_id), INDEX IDX_90D3F060E5AADE30 (smoking_id), INDEX IDX_90D3F0602A89600A (hair_id), INDEX IDX_90D3F060D0B862C7 (zodiaque_id), INDEX IDX_90D3F060D9970B58 (eyes_id), INDEX IDX_90D3F0604296D31F (genre_id), INDEX IDX_90D3F060D5A334C6 (preference_contact_id), INDEX IDX_90D3F060D725330D (agence_id), INDEX IDX_90D3F06065ADB75C (search_instruction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adherent_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, started_at DATE NOT NULL, ending_at DATE NOT NULL, link_picture LONGTEXT NOT NULL, address_street LONGTEXT NOT NULL, address_street2 LONGTEXT DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19AA9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06087D0EECC FOREIGN KEY (status_matrimoniale_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060B6BC8E3 FOREIGN KEY (status_meet_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06062A10F76 FOREIGN KEY (instruction_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06087335AF1 FOREIGN KEY (lodging_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060E5AADE30 FOREIGN KEY (smoking_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0602A89600A FOREIGN KEY (hair_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060D0B862C7 FOREIGN KEY (zodiaque_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060D9970B58 FOREIGN KEY (eyes_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0604296D31F FOREIGN KEY (genre_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060D5A334C6 FOREIGN KEY (preference_contact_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06065ADB75C FOREIGN KEY (search_instruction_id) REFERENCES adherent_option (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06087D0EECC');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060B6BC8E3');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06062A10F76');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06087335AF1');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060E5AADE30');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0602A89600A');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060D0B862C7');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060D9970B58');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0604296D31F');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060D5A334C6');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06065ADB75C');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060D725330D');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE adherent_option');
        $this->addSql('DROP TABLE agence');
    }
}
