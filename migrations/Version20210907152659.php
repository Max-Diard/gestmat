<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907152659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, comments1 LONGTEXT DEFAULT NULL, comments2 LONGTEXT DEFAULT NULL, comments3 LONGTEXT DEFAULT NULL, phone_mobile VARCHAR(255) NOT NULL, phone_home VARCHAR(255) DEFAULT NULL, phone_work VARCHAR(255) DEFAULT NULL, phone_comments VARCHAR(255) DEFAULT NULL, profession LONGTEXT NOT NULL, size DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, permis TINYINT(1) NOT NULL, car TINYINT(1) NOT NULL, announcement_publish TINYINT(1) NOT NULL, announcement_presentation LONGTEXT NOT NULL, announcement_free LONGTEXT DEFAULT NULL, announcement_date_free DATE DEFAULT NULL, announcement_newspaper LONGTEXT DEFAULT NULL, announcement_date_newspaper DATE DEFAULT NULL, owner TINYINT(1) NOT NULL, email VARCHAR(255) NOT NULL, link_picture LONGTEXT DEFAULT NULL, link_contrat LONGTEXT DEFAULT NULL, link_information LONGTEXT DEFAULT NULL, link_picture_announcement LONGTEXT DEFAULT NULL, address_street LONGTEXT NOT NULL, address_street2 LONGTEXT DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, child_girl INT NOT NULL, child_boy INT NOT NULL, child_dependent_girl INT NOT NULL, child_dependent_boy INT NOT NULL, search_age_min INT NOT NULL, search_age_max INT NOT NULL, search_single TINYINT(1) NOT NULL, search_divorced TINYINT(1) NOT NULL, search_windower TINYINT(1) NOT NULL, search_profession LONGTEXT DEFAULT NULL, search_region VARCHAR(255) DEFAULT NULL, search_more LONGTEXT DEFAULT NULL, search_accept_children TINYINT(1) NOT NULL, search_number_accept_children INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adherent_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, started_at DATE NOT NULL, ending_at DATE NOT NULL, link_picture LONGTEXT NOT NULL, address_street LONGTEXT NOT NULL, address_street2 LONGTEXT DEFAULT NULL, address_zip_postal INT NOT NULL, address_town VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19AA9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE adherent_option');
        $this->addSql('DROP TABLE agence');
    }
}
