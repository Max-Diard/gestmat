<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025101324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, status_matrimoniale_id INT DEFAULT NULL, status_meet_id INT DEFAULT NULL, instruction_id INT DEFAULT NULL, lodging_id INT DEFAULT NULL, smoking_id INT DEFAULT NULL, hair_id INT DEFAULT NULL, zodiaque_id INT DEFAULT NULL, eyes_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, preference_contact_id INT DEFAULT NULL, search_instruction_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, type_payment_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, birthdate DATE NOT NULL, comments1 LONGTEXT DEFAULT NULL, comments2 LONGTEXT DEFAULT NULL, comments3 LONGTEXT DEFAULT NULL, phone_mobile VARCHAR(255) NOT NULL, phone_home VARCHAR(255) DEFAULT NULL, phone_work VARCHAR(255) DEFAULT NULL, phone_comments VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) NOT NULL, size NUMERIC(3, 2) NOT NULL, weight NUMERIC(3, 0) NOT NULL, permis TINYINT(1) NOT NULL, car TINYINT(1) NOT NULL, announcement_publish TINYINT(1) NOT NULL, announcement_presentation LONGTEXT NOT NULL, announcement_free LONGTEXT DEFAULT NULL, announcement_date_free DATE DEFAULT NULL, announcement_newspaper LONGTEXT DEFAULT NULL, announcement_date_newspaper DATE DEFAULT NULL, email VARCHAR(255) NOT NULL, link_picture VARCHAR(255) DEFAULT NULL, link_contract LONGTEXT DEFAULT NULL, link_information LONGTEXT DEFAULT NULL, link_picture_announcement LONGTEXT DEFAULT NULL, address_street VARCHAR(255) NOT NULL, address_street2 VARCHAR(255) DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, child_girl INT NOT NULL, child_boy INT NOT NULL, child_dependent_girl INT NOT NULL, child_dependent_boy INT NOT NULL, search_age_min INT NOT NULL, search_age_max INT NOT NULL, search_single TINYINT(1) NOT NULL, search_divorced TINYINT(1) NOT NULL, search_widower TINYINT(1) NOT NULL, search_profession VARCHAR(255) DEFAULT NULL, search_region VARCHAR(255) DEFAULT NULL, search_more LONGTEXT DEFAULT NULL, search_accept_children TINYINT(1) NOT NULL, search_number_accept_children INT NOT NULL, contract_date DATE NOT NULL, contract_started_at DATE NOT NULL, contract_ending_at DATE NOT NULL, contract_amount INT NOT NULL, contract_comments LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_90D3F06087D0EECC (status_matrimoniale_id), INDEX IDX_90D3F060B6BC8E3 (status_meet_id), INDEX IDX_90D3F06062A10F76 (instruction_id), INDEX IDX_90D3F06087335AF1 (lodging_id), INDEX IDX_90D3F060E5AADE30 (smoking_id), INDEX IDX_90D3F0602A89600A (hair_id), INDEX IDX_90D3F060D0B862C7 (zodiaque_id), INDEX IDX_90D3F060D9970B58 (eyes_id), INDEX IDX_90D3F0604296D31F (genre_id), INDEX IDX_90D3F060D5A334C6 (preference_contact_id), INDEX IDX_90D3F06065ADB75C (search_instruction_id), INDEX IDX_90D3F0607E3C61F9 (owner_id), INDEX IDX_90D3F06019C0759E (type_payment_id), INDEX IDX_90D3F060D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adherent_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(45) NOT NULL, started_at DATE NOT NULL, ending_at DATE NOT NULL, link_picture VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street2 VARCHAR(255) DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence_agence (agence_source INT NOT NULL, agence_target INT NOT NULL, INDEX IDX_930D7F8DA8469A57 (agence_source), INDEX IDX_930D7F8DB1A3CAD8 (agence_target), PRIMARY KEY(agence_source, agence_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meet (id INT AUTO_INCREMENT NOT NULL, adherent_woman_id INT NOT NULL, adherent_man_id INT NOT NULL, status_meet_woman_id INT DEFAULT NULL, status_meet_man_id INT DEFAULT NULL, action_meet_woman_id INT DEFAULT NULL, action_meet_man_id INT DEFAULT NULL, started_at DATE NOT NULL, return_at_woman DATE DEFAULT NULL, return_at_man DATE DEFAULT NULL, comments_woman LONGTEXT DEFAULT NULL, comments_man LONGTEXT DEFAULT NULL, INDEX IDX_E9F6D3CEE60D7A92 (adherent_woman_id), INDEX IDX_E9F6D3CEF701299A (adherent_man_id), INDEX IDX_E9F6D3CE21F25DFE (status_meet_woman_id), INDEX IDX_E9F6D3CE7A61CCA2 (status_meet_man_id), INDEX IDX_E9F6D3CE37C85E77 (action_meet_woman_id), INDEX IDX_E9F6D3CE5693097 (action_meet_man_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_agence (user_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_1938194A76ED395 (user_id), INDEX IDX_1938194D725330D (agence_id), PRIMARY KEY(user_id, agence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
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
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06065ADB75C FOREIGN KEY (search_instruction_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0607E3C61F9 FOREIGN KEY (owner_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06019C0759E FOREIGN KEY (type_payment_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE agence_agence ADD CONSTRAINT FK_930D7F8DA8469A57 FOREIGN KEY (agence_source) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agence_agence ADD CONSTRAINT FK_930D7F8DB1A3CAD8 FOREIGN KEY (agence_target) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CEE60D7A92 FOREIGN KEY (adherent_woman_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CEF701299A FOREIGN KEY (adherent_man_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE21F25DFE FOREIGN KEY (status_meet_woman_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE7A61CCA2 FOREIGN KEY (status_meet_man_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE37C85E77 FOREIGN KEY (action_meet_woman_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE5693097 FOREIGN KEY (action_meet_man_id) REFERENCES adherent_option (id)');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CEE60D7A92');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CEF701299A');
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
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0607E3C61F9');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06019C0759E');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE21F25DFE');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE7A61CCA2');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE37C85E77');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE5693097');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060D725330D');
        $this->addSql('ALTER TABLE agence_agence DROP FOREIGN KEY FK_930D7F8DA8469A57');
        $this->addSql('ALTER TABLE agence_agence DROP FOREIGN KEY FK_930D7F8DB1A3CAD8');
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194D725330D');
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194A76ED395');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE adherent_option');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE agence_agence');
        $this->addSql('DROP TABLE meet');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_agence');
    }
}
