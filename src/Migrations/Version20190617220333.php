<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617220333 extends AbstractMigration
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
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9322EFD419');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70');
        $this->addSql('DROP INDEX UNIQ_7D053A93727ACA70 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A9322EFD419 ON menu');
        $this->addSql('ALTER TABLE menu ADD pid INT DEFAULT NULL, DROP parent_id, DROP parents_of_parents_id, CHANGE page_id page_id INT DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A935550C4ED FOREIGN KEY (pid) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_7D053A935550C4ED ON menu (pid)');
        $this->addSql('ALTER TABLE page CHANGE alis_en alis_en VARCHAR(255) DEFAULT NULL');
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
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A935550C4ED');
        $this->addSql('DROP INDEX IDX_7D053A935550C4ED ON menu');
        $this->addSql('ALTER TABLE menu ADD parent_id INT DEFAULT NULL, ADD parents_of_parents_id INT DEFAULT NULL, DROP pid, CHANGE page_id page_id INT DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9322EFD419 FOREIGN KEY (parents_of_parents_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A93727ACA70 ON menu (parent_id)');
        $this->addSql('CREATE INDEX IDX_7D053A9322EFD419 ON menu (parents_of_parents_id)');
        $this->addSql('ALTER TABLE page CHANGE alis_en alis_en VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}
