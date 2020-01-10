<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200110074808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE training ADD image_filename VARCHAR(255) DEFAULT NULL, CHANGE costs costs NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE person CHANGE hiring_date hiring_date DATE DEFAULT NULL, CHANGE salary salary NUMERIC(10, 2) DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE postalcode postalcode VARCHAR(50) DEFAULT NULL, CHANGE place place VARCHAR(255) DEFAULT NULL, CHANGE is_disabled is_disabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE registration CHANGE payment payment VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person CHANGE hiring_date hiring_date DATE DEFAULT \'NULL\', CHANGE salary salary NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE street street VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postalcode postalcode VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE place place VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_disabled is_disabled TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE registration CHANGE payment payment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE training DROP image_filename, CHANGE costs costs NUMERIC(10, 2) DEFAULT \'NULL\'');
    }
}
