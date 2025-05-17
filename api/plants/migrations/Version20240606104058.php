<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606104058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE locations_id_location_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE locations ALTER id_location TYPE INT');
        $this->addSql('COMMENT ON COLUMN locations.id_location IS NULL');
        $this->addSql('ALTER TABLE plants ALTER id_location TYPE INT');
        $this->addSql('ALTER TABLE plants ALTER image SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN plants.id_location IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE locations_id_location_seq CASCADE');
        $this->addSql('ALTER TABLE locations ALTER id_location TYPE UUID');
        $this->addSql('COMMENT ON COLUMN locations.id_location IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE plants ALTER id_location TYPE UUID');
        $this->addSql('ALTER TABLE plants ALTER image DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN plants.id_location IS \'(DC2Type:uuid)\'');
    }
}
