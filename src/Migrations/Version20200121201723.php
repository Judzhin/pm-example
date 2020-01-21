<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200121201723
 * @package DoctrineMigrations
 */
final class Version20200121201723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE work_project_roles (id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E5B65E6D166D1F9C ON work_project_roles (project_id)');
        $this->addSql('COMMENT ON COLUMN work_project_roles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_project_roles.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_project_departments (id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11EC64AE166D1F9C ON work_project_departments (project_id)');
        $this->addSql('COMMENT ON COLUMN work_project_departments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_project_departments.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_projects (id UUID NOT NULL, name VARCHAR(255) NOT NULL, sort INT NOT NULL, version INT DEFAULT 1 NOT NULL, status VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN work_projects.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_project_memberships (id UUID NOT NULL, project_id UUID NOT NULL, member_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_81189B0C166D1F9C ON work_project_memberships (project_id)');
        $this->addSql('CREATE INDEX IDX_81189B0C7597D3FE ON work_project_memberships (member_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81189B0C166D1F9C7597D3FE ON work_project_memberships (project_id, member_id)');
        $this->addSql('COMMENT ON COLUMN work_project_memberships.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_project_memberships.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_project_memberships.member_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_memberships_departments (membership_id UUID NOT NULL, department_id UUID NOT NULL, PRIMARY KEY(membership_id, department_id))');
        $this->addSql('CREATE INDEX IDX_23650C6E1FB354CD ON work_memberships_departments (membership_id)');
        $this->addSql('CREATE INDEX IDX_23650C6EAE80F5DF ON work_memberships_departments (department_id)');
        $this->addSql('COMMENT ON COLUMN work_memberships_departments.membership_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_memberships_departments.department_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE work_memberships_roles (membership_id UUID NOT NULL, role_id UUID NOT NULL, PRIMARY KEY(membership_id, role_id))');
        $this->addSql('CREATE INDEX IDX_2C85E6C11FB354CD ON work_memberships_roles (membership_id)');
        $this->addSql('CREATE INDEX IDX_2C85E6C1D60322AC ON work_memberships_roles (role_id)');
        $this->addSql('COMMENT ON COLUMN work_memberships_roles.membership_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN work_memberships_roles.role_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE work_project_roles ADD CONSTRAINT FK_E5B65E6D166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_project_departments ADD CONSTRAINT FK_11EC64AE166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_project_memberships ADD CONSTRAINT FK_81189B0C166D1F9C FOREIGN KEY (project_id) REFERENCES work_projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_project_memberships ADD CONSTRAINT FK_81189B0C7597D3FE FOREIGN KEY (member_id) REFERENCES work_members (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_memberships_departments ADD CONSTRAINT FK_23650C6E1FB354CD FOREIGN KEY (membership_id) REFERENCES work_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_memberships_departments ADD CONSTRAINT FK_23650C6EAE80F5DF FOREIGN KEY (department_id) REFERENCES work_project_departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_memberships_roles ADD CONSTRAINT FK_2C85E6C11FB354CD FOREIGN KEY (membership_id) REFERENCES work_project_memberships (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_memberships_roles ADD CONSTRAINT FK_2C85E6C1D60322AC FOREIGN KEY (role_id) REFERENCES work_project_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_members ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE work_members ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE work_members ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(16)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER new_email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER new_email DROP DEFAULT');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE work_memberships_roles DROP CONSTRAINT FK_2C85E6C1D60322AC');
        $this->addSql('ALTER TABLE work_memberships_departments DROP CONSTRAINT FK_23650C6EAE80F5DF');
        $this->addSql('ALTER TABLE work_project_roles DROP CONSTRAINT FK_E5B65E6D166D1F9C');
        $this->addSql('ALTER TABLE work_project_departments DROP CONSTRAINT FK_11EC64AE166D1F9C');
        $this->addSql('ALTER TABLE work_project_memberships DROP CONSTRAINT FK_81189B0C166D1F9C');
        $this->addSql('ALTER TABLE work_memberships_departments DROP CONSTRAINT FK_23650C6E1FB354CD');
        $this->addSql('ALTER TABLE work_memberships_roles DROP CONSTRAINT FK_2C85E6C11FB354CD');
        $this->addSql('DROP TABLE work_project_roles');
        $this->addSql('DROP TABLE work_project_departments');
        $this->addSql('DROP TABLE work_projects');
        $this->addSql('DROP TABLE work_project_memberships');
        $this->addSql('DROP TABLE work_memberships_departments');
        $this->addSql('DROP TABLE work_memberships_roles');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER new_email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE users ALTER new_email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE work_members ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE work_members ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE work_members ALTER status TYPE VARCHAR(255)');
    }
}
