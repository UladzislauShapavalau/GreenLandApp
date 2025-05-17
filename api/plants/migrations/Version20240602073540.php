<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602073540 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [plant_types]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE plant_types (
            id_plant_type UUID NOT NULL,
            plant_type_name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id_plant_type))
        ');
        $this->addSql("COMMENT ON COLUMN plant_types.id_plant_type IS '(DC2Type:uuid)'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE plant_types');
    }
}
