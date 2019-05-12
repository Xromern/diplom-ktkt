<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512151950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_teacher ADD code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_teacher ADD CONSTRAINT FK_604DE83027DAFE17 FOREIGN KEY (code_id) REFERENCES journal_code (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_604DE83027DAFE17 ON journal_teacher (code_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE journal_teacher DROP FOREIGN KEY FK_604DE83027DAFE17');
        $this->addSql('DROP INDEX UNIQ_604DE83027DAFE17 ON journal_teacher');
        $this->addSql('ALTER TABLE journal_teacher DROP code_id');
    }
}
