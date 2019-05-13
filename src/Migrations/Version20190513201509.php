<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513201509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subject_teachers');
        $this->addSql('ALTER TABLE journal_subject CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE date date DATE DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark CHANGE mark mark VARCHAR(2) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_D57A5F9EDCE06409 (journal_teacher_id), INDEX IDX_D57A5F9E9F5EC693 (journal_subject_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9E9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9EDCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE date date DATE NOT NULL, CHANGE description description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE journal_mark CHANGE mark mark VARCHAR(2) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE journal_subject CHANGE description description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
