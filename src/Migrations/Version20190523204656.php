<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190523204656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE advertisement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_categories_list (article_id INT NOT NULL, category_article_id INT NOT NULL, INDEX IDX_4DCF66AA7294869C (article_id), INDEX IDX_4DCF66AA548AD6E2 (category_article_id), PRIMARY KEY(article_id, category_article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, text LONGTEXT NOT NULL, update_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_code (id INT AUTO_INCREMENT NOT NULL, key_p VARCHAR(255) NOT NULL, date_use DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_date_mark (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, type_mark_id INT DEFAULT NULL, date DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(255) NOT NULL, page INT NOT NULL, INDEX IDX_F4C7C00023EDC87 (subject_id), INDEX IDX_F4C7C0006AB19EDE (type_mark_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_grading_system (id INT AUTO_INCREMENT NOT NULL, system INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_group (id INT AUTO_INCREMENT NOT NULL, curator_id INT DEFAULT NULL, specialty_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, alis_en VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8E1B77EF733D5B5D (curator_id), INDEX IDX_8E1B77EF9A353316 (specialty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_mark (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, date_mark_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, mark VARCHAR(2) DEFAULT NULL, INDEX IDX_DE4CCAF9CB944F1A (student_id), INDEX IDX_DE4CCAF97B77F6D6 (date_mark_id), INDEX IDX_DE4CCAF923EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_student (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, code_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6798E1D6FE54D947 (group_id), UNIQUE INDEX UNIQ_6798E1D627DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_students (journal_student_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_9C14CDDA56F4550E (journal_student_id), INDEX IDX_9C14CDDA9F5EC693 (journal_subject_id), PRIMARY KEY(journal_student_id, journal_subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_subject (id INT AUTO_INCREMENT NOT NULL, main_teacher_id INT DEFAULT NULL, group_id INT DEFAULT NULL, type_form_control_id INT DEFAULT NULL, grading_system_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, alis_en VARCHAR(255) NOT NULL, INDEX IDX_2B75709F79780A7E (main_teacher_id), INDEX IDX_2B75709FFE54D947 (group_id), INDEX IDX_2B75709FCC4D1FEE (type_form_control_id), INDEX IDX_2B75709FE383D86E (grading_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_teacher (id INT AUTO_INCREMENT NOT NULL, code_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_604DE83027DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_secondary_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_C6DDEB47DCE06409 (journal_teacher_id), INDEX IDX_C6DDEB479F5EC693 (journal_subject_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_type_form_control (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_type_mark (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles_categories_list ADD CONSTRAINT FK_4DCF66AA7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories_list ADD CONSTRAINT FK_4DCF66AA548AD6E2 FOREIGN KEY (category_article_id) REFERENCES category_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE journal_date_mark ADD CONSTRAINT FK_F4C7C00023EDC87 FOREIGN KEY (subject_id) REFERENCES journal_subject (id)');
        $this->addSql('ALTER TABLE journal_date_mark ADD CONSTRAINT FK_F4C7C0006AB19EDE FOREIGN KEY (type_mark_id) REFERENCES journal_type_mark (id)');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF733D5B5D FOREIGN KEY (curator_id) REFERENCES journal_teacher (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF9A353316 FOREIGN KEY (specialty_id) REFERENCES journal_specialty (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF97B77F6D6 FOREIGN KEY (date_mark_id) REFERENCES journal_date_mark (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF923EDC87 FOREIGN KEY (subject_id) REFERENCES journal_subject (id)');
        $this->addSql('ALTER TABLE journal_student ADD CONSTRAINT FK_6798E1D6FE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('ALTER TABLE journal_student ADD CONSTRAINT FK_6798E1D627DAFE17 FOREIGN KEY (code_id) REFERENCES journal_code (id)');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA56F4550E FOREIGN KEY (journal_student_id) REFERENCES journal_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F79780A7E FOREIGN KEY (main_teacher_id) REFERENCES journal_teacher (id)');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709FFE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709FCC4D1FEE FOREIGN KEY (type_form_control_id) REFERENCES journal_type_form_control (id)');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709FE383D86E FOREIGN KEY (grading_system_id) REFERENCES journal_grading_system (id)');
        $this->addSql('ALTER TABLE journal_teacher ADD CONSTRAINT FK_604DE83027DAFE17 FOREIGN KEY (code_id) REFERENCES journal_code (id)');
        $this->addSql('ALTER TABLE subject_secondary_teachers ADD CONSTRAINT FK_C6DDEB47DCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_secondary_teachers ADD CONSTRAINT FK_C6DDEB479F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE articles_categories_list DROP FOREIGN KEY FK_4DCF66AA7294869C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE articles_categories_list DROP FOREIGN KEY FK_4DCF66AA548AD6E2');
        $this->addSql('ALTER TABLE journal_student DROP FOREIGN KEY FK_6798E1D627DAFE17');
        $this->addSql('ALTER TABLE journal_teacher DROP FOREIGN KEY FK_604DE83027DAFE17');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF97B77F6D6');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709FE383D86E');
        $this->addSql('ALTER TABLE journal_student DROP FOREIGN KEY FK_6798E1D6FE54D947');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709FFE54D947');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF9A353316');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9CB944F1A');
        $this->addSql('ALTER TABLE subject_students DROP FOREIGN KEY FK_9C14CDDA56F4550E');
        $this->addSql('ALTER TABLE journal_date_mark DROP FOREIGN KEY FK_F4C7C00023EDC87');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF923EDC87');
        $this->addSql('ALTER TABLE subject_students DROP FOREIGN KEY FK_9C14CDDA9F5EC693');
        $this->addSql('ALTER TABLE subject_secondary_teachers DROP FOREIGN KEY FK_C6DDEB479F5EC693');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF733D5B5D');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F79780A7E');
        $this->addSql('ALTER TABLE subject_secondary_teachers DROP FOREIGN KEY FK_C6DDEB47DCE06409');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709FCC4D1FEE');
        $this->addSql('ALTER TABLE journal_date_mark DROP FOREIGN KEY FK_F4C7C0006AB19EDE');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE articles_categories_list');
        $this->addSql('DROP TABLE category_article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE journal_code');
        $this->addSql('DROP TABLE journal_date_mark');
        $this->addSql('DROP TABLE journal_grading_system');
        $this->addSql('DROP TABLE journal_group');
        $this->addSql('DROP TABLE journal_mark');
        $this->addSql('DROP TABLE journal_specialty');
        $this->addSql('DROP TABLE journal_student');
        $this->addSql('DROP TABLE subject_students');
        $this->addSql('DROP TABLE journal_subject');
        $this->addSql('DROP TABLE journal_teacher');
        $this->addSql('DROP TABLE subject_secondary_teachers');
        $this->addSql('DROP TABLE journal_type_form_control');
        $this->addSql('DROP TABLE journal_type_mark');
        $this->addSql('DROP TABLE user');
    }
}
