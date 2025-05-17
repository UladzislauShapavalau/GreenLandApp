<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602075827 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [locations]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE locations (
            id_location UUID NOT NULL,
            id_user UUID NOT NULL,
            name_location VARCHAR(255) NOT NULL,
            PRIMARY KEY(id_location))
        ');
        $this->addSql('CREATE INDEX IDX_17E64ABA6B3CA4B ON locations (id_user)');
        $this->addSql("COMMENT ON COLUMN locations.id_location IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN locations.id_user IS '(DC2Type:uuid)'");
        $this->addSql('ALTER TABLE locations ADD CONSTRAINT FK_17E64ABA6B3CA4B 
            FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX uniq_8d93d649e7927c74 RENAME TO UNIQ_1483A5E9E7927C74');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE locations DROP CONSTRAINT FK_17E64ABA6B3CA4B');
        $this->addSql('DROP TABLE locations');
        $this->addSql('ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO uniq_8d93d649e7927c74');
    }
}
