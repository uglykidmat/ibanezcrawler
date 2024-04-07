<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407103433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS guitar_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE IF NOT EXISTS neck_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE IF NOT EXISTS guitar (id INT NOT NULL, model VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, modelname VARCHAR(255) NOT NULL, soldin VARCHAR(255) NOT NULL, madein VARCHAR(255) NOT NULL, bodytype VARCHAR(255) NOT NULL, bodymaterial VARCHAR(255) NOT NULL, neckjoint VARCHAR(255) NOT NULL, knobstyle VARCHAR(255) DEFAULT NULL, hardwarecolor VARCHAR(255) DEFAULT NULL, necktype VARCHAR(255) NOT NULL, neckmaterial VARCHAR(255) NOT NULL, scalelength VARCHAR(255) NOT NULL, fingerboardmaterial VARCHAR(255) DEFAULT NULL, fingerboardinlays VARCHAR(255) DEFAULT NULL, machineheads VARCHAR(255) DEFAULT NULL, pickupconfiguration VARCHAR(255) NOT NULL, bridgepickup VARCHAR(255) DEFAULT NULL, middlepickup VARCHAR(255) DEFAULT NULL, neckpickup VARCHAR(255) DEFAULT NULL, outputjack VARCHAR(255) DEFAULT NULL, factorytuning VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE IF NOT EXISTS neck (id INT NOT NULL, type VARCHAR(255) NOT NULL, years VARCHAR(255) NOT NULL, scale_length VARCHAR(255) DEFAULT NULL, width_at_nut VARCHAR(255) DEFAULT NULL, width_at_last_fret VARCHAR(255) DEFAULT NULL, thickness_at1st_fret VARCHAR(255) DEFAULT NULL, thickness_at12th_fret VARCHAR(255) DEFAULT NULL, radius VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP SEQUENCE IF EXISTS guitar_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE IF EXISTS neck_id_seq CASCADE');
        $this->addSql('DROP TABLE IF EXISTS guitar');
        $this->addSql('DROP TABLE IF EXISTS neck');
    }
}
