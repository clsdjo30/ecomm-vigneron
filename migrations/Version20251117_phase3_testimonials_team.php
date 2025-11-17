<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour la Phase 3 : Refonte Design Complet
 * - Ajoute la table testimonial (témoignages clients)
 * - Ajoute la table team_member (équipe)
 */
final class Version20251117_phase3_testimonials_team extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 3: Ajout des tables testimonial et team_member pour le design du site';
    }

    public function up(Schema $schema): void
    {
        // Table testimonial
        $this->addSql('CREATE TABLE testimonial (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            position VARCHAR(100) DEFAULT NULL,
            content TEXT NOT NULL,
            rating INTEGER DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            is_published BOOLEAN NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            display_order INTEGER NOT NULL DEFAULT 0
        )');
        $this->addSql('CREATE INDEX idx_testimonial_published ON testimonial (is_published)');
        $this->addSql('CREATE INDEX idx_testimonial_display_order ON testimonial (display_order)');

        // Table team_member
        $this->addSql('CREATE TABLE team_member (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            role VARCHAR(100) NOT NULL,
            bio TEXT DEFAULT NULL,
            photo VARCHAR(255) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            linkedin_url VARCHAR(255) DEFAULT NULL,
            is_active BOOLEAN NOT NULL DEFAULT 1,
            display_order INTEGER NOT NULL DEFAULT 0
        )');
        $this->addSql('CREATE INDEX idx_team_member_active ON team_member (is_active)');
        $this->addSql('CREATE INDEX idx_team_member_display_order ON team_member (display_order)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS testimonial');
        $this->addSql('DROP TABLE IF EXISTS team_member');
    }
}
