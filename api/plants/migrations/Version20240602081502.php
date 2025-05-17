<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602081502 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [plants]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE plants (
            id_plant UUID NOT NULL,
            id_user UUID NOT NULL,
            id_location UUID NOT NULL,
            id_plant_type UUID NOT NULL,
            image BYTEA DEFAULT NULL,
            nickname VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            adoption_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            description TEXT DEFAULT NULL,
            PRIMARY KEY(id_plant))
        ');
        $this->addSql('CREATE INDEX IDX_A5AEDC166B3CA4B ON plants (id_user)');
        $this->addSql('CREATE INDEX IDX_A5AEDC16E45655E ON plants (id_location)');
        $this->addSql('CREATE INDEX IDX_A5AEDC164ABC17EC ON plants (id_plant_type)');
        $this->addSql("COMMENT ON COLUMN plants.id_plant IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN plants.id_user IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN plants.id_location IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN plants.id_plant_type IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN plants.adoption_date IS '(DC2Type:datetime_immutable)'");
        $this->addSql('ALTER TABLE plants ADD CONSTRAINT FK_A5AEDC166B3CA4B 
            FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plants ADD CONSTRAINT FK_A5AEDC16E45655E 
            FOREIGN KEY (id_location) REFERENCES locations (id_location) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plants ADD CONSTRAINT FK_A5AEDC164ABC17EC 
            FOREIGN KEY (id_plant_type) REFERENCES plant_types (id_plant_type) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plants DROP CONSTRAINT FK_A5AEDC166B3CA4B');
        $this->addSql('ALTER TABLE plants DROP CONSTRAINT FK_A5AEDC16E45655E');
        $this->addSql('ALTER TABLE plants DROP CONSTRAINT FK_A5AEDC164ABC17EC');
        $this->addSql('DROP TABLE plants');
    }
}
