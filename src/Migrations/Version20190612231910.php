<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612231910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_code CHANGE user_id user_id INT DEFAULT NULL, CHANGE date_use date_use DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE type_mark_id type_mark_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_group CHANGE curator_id curator_id INT DEFAULT NULL, CHANGE specialty_id specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark CHANGE student_id student_id INT DEFAULT NULL, CHANGE date_mark_id date_mark_id INT DEFAULT NULL, CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE mark mark VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_student CHANGE group_id group_id INT DEFAULT NULL, CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject CHANGE main_teacher_id main_teacher_id INT DEFAULT NULL, CHANGE group_id group_id INT DEFAULT NULL, CHANGE type_form_control_id type_form_control_id INT DEFAULT NULL, CHANGE grading_system_id grading_system_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_teacher CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_code CHANGE user_id user_id INT DEFAULT NULL, CHANGE date_use date_use DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE journal_date_mark CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE type_mark_id type_mark_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE journal_group CHANGE curator_id curator_id INT DEFAULT NULL, CHANGE specialty_id specialty_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark CHANGE student_id student_id INT DEFAULT NULL, CHANGE date_mark_id date_mark_id INT DEFAULT NULL, CHANGE subject_id subject_id INT DEFAULT NULL, CHANGE mark mark VARCHAR(2) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE journal_student CHANGE group_id group_id INT DEFAULT NULL, CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject CHANGE main_teacher_id main_teacher_id INT DEFAULT NULL, CHANGE group_id group_id INT DEFAULT NULL, CHANGE type_form_control_id type_form_control_id INT DEFAULT NULL, CHANGE grading_system_id grading_system_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_teacher CHANGE code_id code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}
