<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602074805 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [users]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id_user UUID NOT NULL,
            email VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            roles JSON NOT NULL,
            creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id_user))
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON users (email)');
        $this->addSql("COMMENT ON COLUMN users.id_user IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN users.creation_date IS '(DC2Type:datetime_immutable)'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE users');
    }
}
