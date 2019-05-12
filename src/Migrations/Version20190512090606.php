<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512090606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE journal_specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_form_control_mark (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, form_control_id INT DEFAULT NULL, mark VARCHAR(2) NOT NULL, INDEX IDX_191930B2CB944F1A (student_id), INDEX IDX_191930B23B798B9A (form_control_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advertisement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_categories_list (article_id INT NOT NULL, category_article_id INT NOT NULL, INDEX IDX_4DCF66AA7294869C (article_id), INDEX IDX_4DCF66AA548AD6E2 (category_article_id), PRIMARY KEY(article_id, category_article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_student (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6798E1D6FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_students (journal_student_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_9C14CDDA56F4550E (journal_student_id), INDEX IDX_9C14CDDA9F5EC693 (journal_subject_id), PRIMARY KEY(journal_student_id, journal_subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_subject (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, alis_en VARCHAR(255) NOT NULL, INDEX IDX_2B75709F41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_code (id INT AUTO_INCREMENT NOT NULL, key_p VARCHAR(255) NOT NULL, date_use DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_date_mark (id INT AUTO_INCREMENT NOT NULL, type_mark_id INT DEFAULT NULL, date DATE NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_F4C7C0006AB19EDE (type_mark_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_group (id INT AUTO_INCREMENT NOT NULL, curator_id INT DEFAULT NULL, specialty_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, alis_en VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8E1B77EF733D5B5D (curator_id), INDEX IDX_8E1B77EF9A353316 (specialty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_type_mark (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, text LONGTEXT NOT NULL, update_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_form_control (id INT AUTO_INCREMENT NOT NULL, type_form_control_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_F7BFF3FCC4D1FEE (type_form_control_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_mark (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, group_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, mark VARCHAR(2) NOT NULL, INDEX IDX_DE4CCAF9CB944F1A (student_id), INDEX IDX_DE4CCAF9FE54D947 (group_id), INDEX IDX_DE4CCAF941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_type_form_control (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B2CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B23B798B9A FOREIGN KEY (form_control_id) REFERENCES journal_form_control (id)');
        $this->addSql('ALTER TABLE articles_categories_list ADD CONSTRAINT FK_4DCF66AA7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories_list ADD CONSTRAINT FK_4DCF66AA548AD6E2 FOREIGN KEY (category_article_id) REFERENCES category_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_student ADD CONSTRAINT FK_6798E1D6FE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA56F4550E FOREIGN KEY (journal_student_id) REFERENCES journal_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_students ADD CONSTRAINT FK_9C14CDDA9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F41807E1D FOREIGN KEY (teacher_id) REFERENCES journal_teacher (id)');
        $this->addSql('ALTER TABLE journal_date_mark ADD CONSTRAINT FK_F4C7C0006AB19EDE FOREIGN KEY (type_mark_id) REFERENCES journal_type_mark (id)');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF733D5B5D FOREIGN KEY (curator_id) REFERENCES journal_teacher (id)');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF9A353316 FOREIGN KEY (specialty_id) REFERENCES journal_specialty (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE journal_form_control ADD CONSTRAINT FK_F7BFF3FCC4D1FEE FOREIGN KEY (type_form_control_id) REFERENCES journal_type_form_control (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9FE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF941807E1D FOREIGN KEY (teacher_id) REFERENCES journal_date_mark (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF9A353316');
        $this->addSql('ALTER TABLE articles_categories_list DROP FOREIGN KEY FK_4DCF66AA7294869C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE journal_form_control_mark DROP FOREIGN KEY FK_191930B2CB944F1A');
        $this->addSql('ALTER TABLE subject_students DROP FOREIGN KEY FK_9C14CDDA56F4550E');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9CB944F1A');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F41807E1D');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF733D5B5D');
        $this->addSql('ALTER TABLE articles_categories_list DROP FOREIGN KEY FK_4DCF66AA548AD6E2');
        $this->addSql('ALTER TABLE subject_students DROP FOREIGN KEY FK_9C14CDDA9F5EC693');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF941807E1D');
        $this->addSql('ALTER TABLE journal_student DROP FOREIGN KEY FK_6798E1D6FE54D947');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9FE54D947');
        $this->addSql('ALTER TABLE journal_date_mark DROP FOREIGN KEY FK_F4C7C0006AB19EDE');
        $this->addSql('ALTER TABLE journal_form_control_mark DROP FOREIGN KEY FK_191930B23B798B9A');
        $this->addSql('ALTER TABLE journal_form_control DROP FOREIGN KEY FK_F7BFF3FCC4D1FEE');
        $this->addSql('DROP TABLE journal_specialty');
        $this->addSql('DROP TABLE journal_form_control_mark');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE articles_categories_list');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE journal_student');
        $this->addSql('DROP TABLE subject_students');
        $this->addSql('DROP TABLE journal_teacher');
        $this->addSql('DROP TABLE category_article');
        $this->addSql('DROP TABLE journal_subject');
        $this->addSql('DROP TABLE journal_code');
        $this->addSql('DROP TABLE journal_date_mark');
        $this->addSql('DROP TABLE journal_group');
        $this->addSql('DROP TABLE journal_type_mark');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE journal_form_control');
        $this->addSql('DROP TABLE journal_mark');
        $this->addSql('DROP TABLE journal_type_form_control');
    }
}
