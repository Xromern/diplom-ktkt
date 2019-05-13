<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513204844 extends AbstractMigration
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
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF941807E1D');
        $this->addSql('DROP INDEX IDX_DE4CCAF941807E1D ON journal_mark');
        $this->addSql('ALTER TABLE journal_mark CHANGE teacher_id date_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9B897366B FOREIGN KEY (date_id) REFERENCES journal_date_mark (id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF9B897366B ON journal_mark (date_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subject_teachers (journal_teacher_id INT NOT NULL, journal_subject_id INT NOT NULL, INDEX IDX_D57A5F9E9F5EC693 (journal_subject_id), INDEX IDX_D57A5F9EDCE06409 (journal_teacher_id), PRIMARY KEY(journal_teacher_id, journal_subject_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9E9F5EC693 FOREIGN KEY (journal_subject_id) REFERENCES journal_subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_teachers ADD CONSTRAINT FK_D57A5F9EDCE06409 FOREIGN KEY (journal_teacher_id) REFERENCES journal_teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9B897366B');
        $this->addSql('DROP INDEX IDX_DE4CCAF9B897366B ON journal_mark');
        $this->addSql('ALTER TABLE journal_mark CHANGE date_id teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF941807E1D FOREIGN KEY (teacher_id) REFERENCES journal_date_mark (id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF941807E1D ON journal_mark (teacher_id)');
    }
}
