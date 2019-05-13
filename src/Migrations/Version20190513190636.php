<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513190636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_secondary_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_C6DDEB47DCE06409 (journal_teacher_id), INDEX IDX_C6DDEB479F5EC693 (journal_subject_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_secondary_teachers ADD CONSTRAINT FK_C6DDEB47DCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_secondary_teachers ADD CONSTRAINT FK_C6DDEB479F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE subject_teachers');
        $this->addSql('ALTER TABLE journal_subject ADD main_teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F79780A7E FOREIGN KEY (main_teacher_id) REFERENCES journal_teacher (id)');
        $this->addSql('CREATE INDEX IDX_2B75709F79780A7E ON journal_subject (main_teacher_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_D57A5F9E9F5EC693 (journal_subject_id), INDEX IDX_D57A5F9EDCE06409 (journal_teacher_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9E9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9EDCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE subject_secondary_teachers');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F79780A7E');
        $this->addSql('DROP INDEX IDX_2B75709F79780A7E ON journal_subject');
        $this->addSql('ALTER TABLE journal_subject DROP main_teacher_id');
    }
}
