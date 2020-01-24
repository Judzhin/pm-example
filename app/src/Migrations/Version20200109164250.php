<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200109164250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE work_members (id UUID NOT NULL, group_id UUID NOT NULL, email VARCHAR(255) NOT NULL, status JSON NOT NULL, name_first VARCHAR(255) NOT NULL, name_last VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906B6ADDFE54D947 ON work_members (group_id)');
        $this->addSql('COMMENT ON COLUMN work_members.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_members.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE work_members ADD CONSTRAINT FK_906B6ADDFE54D947 FOREIGN KEY (group_id) REFERENCES work_member_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER new_email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER new_email DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE work_members');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER new_email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER new_email DROP DEFAULT');
    }
}
