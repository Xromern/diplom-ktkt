<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513215053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_form_control_mark DROP FOREIGN KEY FK_191930B23B798B9A');
        $this->addSql('DROP TABLE journal_form_control');
        $this->addSql('DROP TABLE journal_form_control_mark');
        $this->addSql('DROP TABLE subject_teachers');
        $this->addSql('ALTER TABLE journal_subject ADD type_form_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709FCC4D1FEE FOREIGN KEY (type_form_control_id) REFERENCES journal_type_form_control (id)');
        $this->addSql('CREATE INDEX IDX_2B75709FCC4D1FEE ON journal_subject (type_form_control_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE journal_form_control (id INT AUTO_INCREMENT NOT NULL, type_form_control_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, date DATE NOT NULL, INDEX IDX_F7BFF3FCC4D1FEE (type_form_control_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE journal_form_control_mark (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, form_control_id INT DEFAULT NULL, mark VARCHAR(2) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_191930B2CB944F1A (student_id), INDEX IDX_191930B23B798B9A (form_control_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE subject_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_D57A5F9E9F5EC693 (journal_subject_id), INDEX IDX_D57A5F9EDCE06409 (journal_teacher_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE journal_form_control ADD CONSTRAINT FK_F7BFF3FCC4D1FEE FOREIGN KEY (type_form_control_id) REFERENCES journal_type_form_control (id)');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B23B798B9A FOREIGN KEY (form_control_id) REFERENCES journal_form_control (id)');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B2CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9E9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9EDCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709FCC4D1FEE');
        $this->addSql('DROP INDEX IDX_2B75709FCC4D1FEE ON journal_subject');
        $this->addSql('ALTER TABLE journal_subject DROP type_form_control_id');
    }
}
