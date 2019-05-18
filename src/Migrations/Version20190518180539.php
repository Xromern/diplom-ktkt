<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518180539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE journal_grading_system (id INT AUTO_INCREMENT NOT NULL, system INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_code CHANGE date_use date_use DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE type_mark_id type_mark_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_group CHANGE curator_id curator_id INT DEFAULT NULL, CHANGE specialty_id specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark CHANGE student_id student_id INT DEFAULT NULL, CHANGE date_id date_id INT DEFAULT NULL, CHANGE mark mark VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_student CHANGE group_id group_id INT DEFAULT NULL, CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject ADD grading_system_id INT DEFAULT NULL, CHANGE main_teacher_id main_teacher_id INT DEFAULT NULL, CHANGE group_id group_id INT DEFAULT NULL, CHANGE type_form_control_id type_form_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709FE383D86E FOREIGN KEY (grading_system_id) REFERENCES journal_grading_system (id)');
        $this->addSql('CREATE INDEX IDX_2B75709FE383D86E ON journal_subject (grading_system_id)');
        $this->addSql('ALTER TABLE journal_teacher CHANGE code_id code_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709FE383D86E');
        $this->addSql('DROP TABLE journal_grading_system');
        $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_code CHANGE date_use date_use DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE type_mark_id type_mark_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE journal_group CHANGE curator_id curator_id INT DEFAULT NULL, CHANGE specialty_id specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark CHANGE student_id student_id INT DEFAULT NULL, CHANGE date_id date_id INT DEFAULT NULL, CHANGE mark mark VARCHAR(2) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE journal_student CHANGE group_id group_id INT DEFAULT NULL, CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_2B75709FE383D86E ON journal_subject');
        $this->addSql('ALTER TABLE journal_subject DROP grading_system_id, CHANGE main_teacher_id main_teacher_id INT DEFAULT NULL, CHANGE group_id group_id INT DEFAULT NULL, CHANGE type_form_control_id type_form_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_teacher CHANGE code_id code_id INT DEFAULT NULL');
    }
}
