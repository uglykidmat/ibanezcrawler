<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015112534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guitar_finish (guitar_id INT NOT NULL, finish_id INT NOT NULL, PRIMARY KEY(guitar_id, finish_id))');
        $this->addSql('CREATE INDEX IDX_F5DD31D048420B1E ON guitar_finish (guitar_id)');
        $this->addSql('CREATE INDEX IDX_F5DD31D02B4667EB ON guitar_finish (finish_id)');
        $this->addSql('ALTER TABLE guitar_finish ADD CONSTRAINT FK_F5DD31D048420B1E FOREIGN KEY (guitar_id) REFERENCES guitar (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guitar_finish ADD CONSTRAINT FK_F5DD31D02B4667EB FOREIGN KEY (finish_id) REFERENCES finish (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE guitar_finish DROP CONSTRAINT FK_F5DD31D048420B1E');
        $this->addSql('ALTER TABLE guitar_finish DROP CONSTRAINT FK_F5DD31D02B4667EB');
        $this->addSql('DROP TABLE guitar_finish');
    }
}
