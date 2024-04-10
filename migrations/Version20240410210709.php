<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410210709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE neck (id INT NOT NULL, type VARCHAR(255) NOT NULL, years VARCHAR(255) NOT NULL, scale_length VARCHAR(255) DEFAULT NULL, width_at_nut VARCHAR(255) DEFAULT NULL, width_at_last_fret VARCHAR(255) DEFAULT NULL, thickness_at1st_fret VARCHAR(255) DEFAULT NULL, thickness_at12th_fret VARCHAR(255) DEFAULT NULL, radius VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE guitar (id INT NOT NULL, model VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, modelname VARCHAR(255) DEFAULT NULL, soldin VARCHAR(255) DEFAULT NULL, madein VARCHAR(255) DEFAULT NULL, bodytype VARCHAR(255) DEFAULT NULL, bodymaterial VARCHAR(255) DEFAULT NULL, neckjoint VARCHAR(255) DEFAULT NULL, knobstyle VARCHAR(255) DEFAULT NULL, hardwarecolor VARCHAR(255) DEFAULT NULL, necktype VARCHAR(255) DEFAULT NULL, neckmaterial VARCHAR(255) DEFAULT NULL, scalelength VARCHAR(255) DEFAULT NULL, fingerboardmaterial VARCHAR(255) DEFAULT NULL, fingerboardinlays VARCHAR(255) DEFAULT NULL, machineheads VARCHAR(255) DEFAULT NULL, pickupconfiguration VARCHAR(255) DEFAULT NULL, bridgepickup VARCHAR(255) DEFAULT NULL, middlepickup VARCHAR(255) DEFAULT NULL, neckpickup VARCHAR(255) DEFAULT NULL, outputjack VARCHAR(255) DEFAULT NULL, factorytuning VARCHAR(255) DEFAULT NULL, strapbuttons VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE neck');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE guitar');
    }
}
