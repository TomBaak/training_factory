<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191217104101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person ADD is_disabled TINYINT(1) NOT NULL, CHANGE hiring_date hiring_date DATE DEFAULT NULL, CHANGE salary salary NUMERIC(10, 2) DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE postalcode postalcode VARCHAR(50) DEFAULT NULL, CHANGE place place VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training CHANGE costs costs NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F364D218E');
        $this->addSql('DROP INDEX location_id ON lesson');
        $this->addSql('CREATE INDEX IDX_F87474F364D218E ON lesson (location_id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F364D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7CDF80196');
        $this->addSql('ALTER TABLE registration CHANGE payment payment VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F364D218E');
        $this->addSql('DROP INDEX idx_f87474f364d218e ON lesson');
        $this->addSql('CREATE INDEX location_id ON lesson (location_id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F364D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE person DROP is_disabled, CHANGE hiring_date hiring_date DATE DEFAULT \'NULL\', CHANGE salary salary NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE street street VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postalcode postalcode VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE place place VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7CDF80196');
        $this->addSql('ALTER TABLE registration CHANGE payment payment TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE training CHANGE costs costs NUMERIC(10, 2) DEFAULT \'NULL\'');
    }
}
