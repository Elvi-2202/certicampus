<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260625102039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certified (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL, label VARCHAR(255) NOT NULL, grade VARCHAR(50) NOT NULL, graduation_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_6F94FE2AD17F50A6 (uuid), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE diploma (id INT AUTO_INCREMENT NOT NULL, file_url VARCHAR(255) NOT NULL, generated_at DATETIME NOT NULL, is_valid TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE speciality (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, duration VARCHAR(50) NOT NULL, is_active TINYINT NOT NULL, school_id INT DEFAULT NULL, INDEX IDX_A3C664D3C32A47EE (school_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE template_diploma (id INT AUTO_INCREMENT NOT NULL, student_name VARCHAR(255) NOT NULL, school_name VARCHAR(255) NOT NULL, director_name VARCHAR(255) NOT NULL, assistant_director_name VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, certificate_date DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_reset_token_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3C32A47EE');
        $this->addSql('DROP TABLE certified');
        $this->addSql('DROP TABLE diploma');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE template_diploma');
        $this->addSql('DROP TABLE training');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
