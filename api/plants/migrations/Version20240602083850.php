<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240602083850 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Table [reminders]: created';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE reminders (
            id_reminder UUID NOT NULL,
            id_plant UUID NOT NULL,
            id_user UUID NOT NULL,
            id_reminder_type UUID NOT NULL,
            start_execution TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            cycle INT NOT NULL,
            PRIMARY KEY(id_reminder))
        ');
        $this->addSql('CREATE INDEX IDX_6D92B9D445864C42 ON reminders (id_plant)');
        $this->addSql('CREATE INDEX IDX_6D92B9D46B3CA4B ON reminders (id_user)');
        $this->addSql('CREATE INDEX IDX_6D92B9D4250A5A65 ON reminders (id_reminder_type)');
        $this->addSql("COMMENT ON COLUMN reminders.id_reminder IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN reminders.id_plant IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN reminders.id_user IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN reminders.id_reminder_type IS '(DC2Type:uuid)'");
        $this->addSql("COMMENT ON COLUMN reminders.start_execution IS '(DC2Type:datetime_immutable)'");
        $this->addSql('ALTER TABLE reminders ADD CONSTRAINT FK_6D92B9D445864C42 
            FOREIGN KEY (id_plant) REFERENCES plants (id_plant) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reminders ADD CONSTRAINT FK_6D92B9D46B3CA4B 
            FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reminders ADD CONSTRAINT FK_6D92B9D4250A5A65 
            FOREIGN KEY (id_reminder_type) REFERENCES reminder_types (id_reminder_type) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reminders DROP CONSTRAINT FK_6D92B9D445864C42');
        $this->addSql('ALTER TABLE reminders DROP CONSTRAINT FK_6D92B9D46B3CA4B');
        $this->addSql('ALTER TABLE reminders DROP CONSTRAINT FK_6D92B9D4250A5A65');
        $this->addSql('DROP TABLE reminders');
    }
}
