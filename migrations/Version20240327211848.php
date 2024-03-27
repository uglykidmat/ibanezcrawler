<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327211848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS necks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE IF NOT EXISTS necks (id INT NOT NULL, type VARCHAR(255) NOT NULL, years VARCHAR(255) NOT NULL, scale_length VARCHAR(255) DEFAULT NULL, width_at_nut VARCHAR(255) DEFAULT NULL, width_at_last_fret VARCHAR(255) DEFAULT NULL, thickness_at1st_fret VARCHAR(255) DEFAULT NULL, thickness_at12th_fret VARCHAR(255) DEFAULT NULL, radius VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP SEQUENCE IF EXISTS necks_id_seq CASCADE');
        $this->addSql('DROP TABLE IF EXISTS necks');
    }
}
