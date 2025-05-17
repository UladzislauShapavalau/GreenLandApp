<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602073308 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [reminder_types]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE reminder_types (
            id_reminder_type UUID NOT NULL,
            reminder_type_name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id_reminder_type))
        ');
        $this->addSql("COMMENT ON COLUMN reminder_types.id_reminder_type IS '(DC2Type:uuid)'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE reminder_types');
    }
}
