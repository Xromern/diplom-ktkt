<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512165702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F41807E1D');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F41807E1D FOREIGN KEY (teacher_id) REFERENCES journal_teacher (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF733D5B5D');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF9A353316');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF733D5B5D FOREIGN KEY (curator_id) REFERENCES journal_teacher (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF9A353316 FOREIGN KEY (specialty_id) REFERENCES journal_specialty (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF733D5B5D');
        $this->addSql('ALTER TABLE journal_group DROP FOREIGN KEY FK_8E1B77EF9A353316');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF733D5B5D FOREIGN KEY (curator_id) REFERENCES journal_teacher (id)');
        $this->addSql('ALTER TABLE journal_group ADD CONSTRAINT FK_8E1B77EF9A353316 FOREIGN KEY (specialty_id) REFERENCES journal_specialty (id)');
        $this->addSql('ALTER TABLE journal_subject DROP FOREIGN KEY FK_2B75709F41807E1D');
        $this->addSql('ALTER TABLE journal_subject ADD CONSTRAINT FK_2B75709F41807E1D FOREIGN KEY (teacher_id) REFERENCES journal_teacher (id)');
    }
}
