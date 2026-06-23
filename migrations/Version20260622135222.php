<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260622135222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_diploma ADD student_name VARCHAR(255) NOT NULL, ADD school_name VARCHAR(255) NOT NULL, ADD director_name VARCHAR(255) NOT NULL, ADD assistant_director_name VARCHAR(255) NOT NULL, ADD identifier VARCHAR(255) NOT NULL, ADD certificate_date DATE NOT NULL, DROP content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_diploma ADD content LONGTEXT NOT NULL, DROP student_name, DROP school_name, DROP director_name, DROP assistant_director_name, DROP identifier, DROP certificate_date');
    }
}
