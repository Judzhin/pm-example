<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20191213234442
 * @package DoctrineMigrations
 */
final class Version20191213234442 extends AbstractMigration
{
    /**
     * @return string
     */
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

        // $this->addSql('ALTER TABLE users ADD name_first VARCHAR(255) NULL');
        // $this->addSql('ALTER TABLE users ADD name_last VARCHAR(255) NULL');
        //
        // $this->addSql('UPDATE users SET name_first =\'\'');
        // $this->addSql('UPDATE users SET name_last =\'\'');
        //
        // $this->addSql('ALTER TABLE users ALTER name_first SET NOT NULL');
        // $this->addSql('ALTER TABLE users ALTER name_last SET NOT NULL');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users DROP name_first');
        $this->addSql('ALTER TABLE users DROP name_last');
    }
}
