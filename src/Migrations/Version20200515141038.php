<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200515141038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note CHANGE prof_id prof_id INT DEFAULT NULL, CHANGE note note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE student CHANGE stdcv_id stdcv_id INT DEFAULT NULL, CHANGE stdchoice_id stdchoice_id INT DEFAULT NULL, CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE stdperinfo_id stdperinfo_id INT DEFAULT NULL, CHANGE classe_id classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD activation_token VARCHAR(55) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE std_profile DROP FOREIGN KEY FK_6257E44F8F5EA509');
        $this->addSql('DROP INDEX IDX_6257E44F8F5EA509 ON std_profile');
        $this->addSql('ALTER TABLE std_profile DROP classe_id');
        $this->addSql('ALTER TABLE prof CHANGE roles roles JSON NOT NULL, CHANGE picture picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE course CHANGE picture picture VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE note CHANGE prof_id prof_id INT DEFAULT NULL, CHANGE note note DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE prof CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE std_profile ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE std_profile ADD CONSTRAINT FK_6257E44F8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_6257E44F8F5EA509 ON std_profile (classe_id)');
        $this->addSql('ALTER TABLE student CHANGE stdcv_id stdcv_id INT DEFAULT NULL, CHANGE stdchoice_id stdchoice_id INT DEFAULT NULL, CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE stdperinfo_id stdperinfo_id INT DEFAULT NULL, CHANGE classe_id classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP activation_token, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
