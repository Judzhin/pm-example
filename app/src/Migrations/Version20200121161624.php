<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121161624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE work_members ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE work_members ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE work_members ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE work_projects ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE work_projects ALTER status DROP DEFAULT');
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
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER new_email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER new_email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE work_members ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE work_members ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE work_projects ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE work_projects ALTER status DROP DEFAULT');
    }
}
