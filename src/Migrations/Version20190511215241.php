<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511215241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_students (journal_student_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_9C14CDDA56F4550E (journal_student_id), INDEX IDX_9C14CDDA9F5EC693 (journal_subject_id), PRIMARY KEY(journal_student_id, journal_subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA56F4550E FOREIGN KEY (journal_student_id) REFERENCES journal_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('ALTER TABLE journal_student DROP FOREIGN KEY FK_6798E1D623EDC87');
        $this->addSql('DROP INDEX IDX_6798E1D623EDC87 ON journal_student');
        $this->addSql('ALTER TABLE journal_student DROP subject_id');
        $this->addSql('ALTER TABLE journal_code ADD student_id INT DEFAULT NULL, ADD `key` VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE journal_code ADD CONSTRAINT FK_CF2D0810CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF2D0810CB944F1A ON journal_code (student_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE subject_students');
        $this->addSql('ALTER TABLE journal_code DROP FOREIGN KEY FK_CF2D0810CB944F1A');
        $this->addSql('DROP INDEX UNIQ_CF2D0810CB944F1A ON journal_code');
        $this->addSql('ALTER TABLE journal_code DROP student_id, DROP `key`');
        $this->addSql('ALTER TABLE journal_student ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_student ADD CONSTRAINT FK_6798E1D623EDC87 FOREIGN KEY (subject_id) REFERENCES journal_subject (id)');
        $this->addSql('CREATE INDEX IDX_6798E1D623EDC87 ON journal_student (subject_id)');
    }
}
