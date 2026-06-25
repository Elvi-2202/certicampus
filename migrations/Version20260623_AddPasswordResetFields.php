<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add password reset fields to user table
 */
final class Version20260623_AddPasswordResetFields extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add password reset token fields to user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD password_reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD password_reset_token_expires_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_password_reset_token ON user (password_reset_token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_password_reset_token ON user');
        $this->addSql('ALTER TABLE user DROP password_reset_token');
        $this->addSql('ALTER TABLE user DROP password_reset_token_expires_at');
    }
}
