<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513142618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prof_classe (prof_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_199FD698ABC1F7FE (prof_id), INDEX IDX_199FD6988F5EA509 (classe_id), PRIMARY KEY(prof_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prof_classe ADD CONSTRAINT FK_199FD698ABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prof_classe ADD CONSTRAINT FK_199FD6988F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE classe_prof');
        $this->addSql('ALTER TABLE note CHANGE prof_id prof_id INT DEFAULT NULL, CHANGE student_id student_id INT DEFAULT NULL, CHANGE note note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE student CHANGE stdcv_id stdcv_id INT DEFAULT NULL, CHANGE stdchoice_id stdchoice_id INT DEFAULT NULL, CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE stdperinfo_id stdperinfo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE prof CHANGE roles roles JSON NOT NULL, CHANGE picture picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE course CHANGE picture picture VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE classe_prof (classe_id INT NOT NULL, prof_id INT NOT NULL, INDEX IDX_45A9055D8F5EA509 (classe_id), INDEX IDX_45A9055DABC1F7FE (prof_id), PRIMARY KEY(classe_id, prof_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE classe_prof ADD CONSTRAINT FK_45A9055D8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_prof ADD CONSTRAINT FK_45A9055DABC1F7FE FOREIGN KEY (prof_id) REFERENCES prof (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE prof_classe');
        $this->addSql('ALTER TABLE course CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE note CHANGE prof_id prof_id INT DEFAULT NULL, CHANGE student_id student_id INT DEFAULT NULL, CHANGE note note DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE prof CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE student CHANGE stdcv_id stdcv_id INT DEFAULT NULL, CHANGE stdchoice_id stdchoice_id INT DEFAULT NULL, CHANGE profile_id profile_id INT DEFAULT NULL, CHANGE stdperinfo_id stdperinfo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
