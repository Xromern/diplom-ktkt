<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513185007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_date_mark ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_date_mark ADD CONSTRAINT FK_F4C7C00023EDC87 FOREIGN KEY (subject_id) REFERENCES journal_subject (id)');
        $this->addSql('CREATE INDEX IDX_F4C7C00023EDC87 ON journal_date_mark (subject_id)');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9FE54D947');
        $this->addSql('DROP INDEX IDX_DE4CCAF9FE54D947 ON journal_mark');
        $this->addSql('ALTER TABLE journal_mark DROP group_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_date_mark DROP FOREIGN KEY FK_F4C7C00023EDC87');
        $this->addSql('DROP INDEX IDX_F4C7C00023EDC87 ON journal_date_mark');
        $this->addSql('ALTER TABLE journal_date_mark DROP subject_id');
        $this->addSql('ALTER TABLE journal_mark ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9FE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF9FE54D947 ON journal_mark (group_id)');
    }
}
