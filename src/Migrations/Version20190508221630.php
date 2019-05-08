<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508221630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fos_user');
        $this->addSql('ALTER TABLE journal_student ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_student ADD CONSTRAINT FK_6798E1D623EDC87 FOREIGN KEY (subject_id) REFERENCES journal_subject (id)');
        $this->addSql('CREATE INDEX IDX_6798E1D623EDC87 ON journal_student (subject_id)');
        $this->addSql('ALTER TABLE journal_subject ADD teacher_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F41807E1D FOREIGN KEY (teacher_id) REFERENCES journal_teacher (id)');
        $this->addSql('CREATE INDEX IDX_2B75709F41807E1D ON journal_subject (teacher_id)');
        $this->addSql('ALTER TABLE journal_group ADD description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE journal_group DROP description');
        $this->addSql('ALTER TABLE journal_student DROP FOREIGN KEY FK_6798E1D623EDC87');
        $this->addSql('DROP INDEX IDX_6798E1D623EDC87 ON journal_student');
        $this->addSql('ALTER TABLE journal_student DROP subject_id');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F41807E1D');
        $this->addSql('DROP INDEX IDX_2B75709F41807E1D ON journal_subject');
        $this->addSql('ALTER TABLE journal_subject DROP teacher_id, DROP name, DROP description');
    }
}
