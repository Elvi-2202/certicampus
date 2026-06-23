<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260623090559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make certified relation optional and add certified foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE diploma CHANGE certified_id certified_id INT DEFAULT NULL');

        $this->addSql('ALTER TABLE diploma ADD CONSTRAINT FK_EC2189572EC69D07 FOREIGN KEY (certified_id) REFERENCES certified (id)');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC2189572EC69D07 ON diploma (certified_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE diploma DROP FOREIGN KEY FK_EC2189572EC69D07');

        $this->addSql('DROP INDEX UNIQ_EC2189572EC69D07 ON diploma');

        $this->addSql('ALTER TABLE diploma CHANGE certified_id certified_id INT NOT NULL');
    }
}