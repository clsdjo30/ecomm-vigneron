<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour la Phase 2 : Newsletter & Contact Amélioré
 * - Ajoute la table newsletter_subscriber
 * - Ajoute la table contact_message
 */
final class Version20251117_newsletter_contact extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 2: Ajout des tables newsletter_subscriber et contact_message';
    }

    public function up(Schema $schema): void
    {
        // Table newsletter_subscriber
        $this->addSql('CREATE TABLE newsletter_subscriber (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            created_at DATETIME NOT NULL,
            is_confirmed BOOLEAN NOT NULL DEFAULT 0,
            confirmation_token VARCHAR(64) DEFAULT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9E9F1E7E7927C74 ON newsletter_subscriber (email)');

        // Table contact_message
        $this->addSql('CREATE TABLE contact_message (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(180) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            subject VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            is_read BOOLEAN NOT NULL DEFAULT 0
        )');
        $this->addSql('CREATE INDEX idx_contact_is_read ON contact_message (is_read)');
        $this->addSql('CREATE INDEX idx_contact_created_at ON contact_message (created_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS newsletter_subscriber');
        $this->addSql('DROP TABLE IF EXISTS contact_message');
    }
}
