<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508231419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE journal_type_form_control (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('ALTER TABLE journal_date_mark ADD type_mark_id INT DEFAULT NULL, ADD date DATE NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE journal_date_mark ADD CONSTRAINT FK_F4C7C0006AB19EDE FOREIGN KEY (type_mark_id) REFERENCES journal_type_mark (id)');
        $this->addSql('CREATE INDEX IDX_F4C7C0006AB19EDE ON journal_date_mark (type_mark_id)');
        $this->addSql('ALTER TABLE journal_type_mark ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE journal_mark ADD student_id INT DEFAULT NULL, ADD group_id INT DEFAULT NULL, ADD teacher_id INT DEFAULT NULL, ADD mark VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF9FE54D947 FOREIGN KEY (group_id) REFERENCES journal_group (id)');
        $this->addSql('ALTER TABLE journal_mark ADD CONSTRAINT FK_DE4CCAF941807E1D FOREIGN KEY (teacher_id) REFERENCES journal_date_mark (id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF9CB944F1A ON journal_mark (student_id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF9FE54D947 ON journal_mark (group_id)');
        $this->addSql('CREATE INDEX IDX_DE4CCAF941807E1D ON journal_mark (teacher_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE journal_type_form_control');
        $this->addSql('ALTER TABLE journal_date_mark DROP FOREIGN KEY FK_F4C7C0006AB19EDE');
        $this->addSql('DROP INDEX IDX_F4C7C0006AB19EDE ON journal_date_mark');
        $this->addSql('ALTER TABLE journal_date_mark DROP type_mark_id, DROP date, DROP description');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9CB944F1A');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF9FE54D947');
        $this->addSql('ALTER TABLE journal_mark DROP FOREIGN KEY FK_DE4CCAF941807E1D');
        $this->addSql('DROP INDEX IDX_DE4CCAF9CB944F1A ON journal_mark');
        $this->addSql('DROP INDEX IDX_DE4CCAF9FE54D947 ON journal_mark');
        $this->addSql('DROP INDEX IDX_DE4CCAF941807E1D ON journal_mark');
        $this->addSql('ALTER TABLE journal_mark DROP student_id, DROP group_id, DROP teacher_id, DROP mark');
        $this->addSql('ALTER TABLE journal_type_mark DROP name');
    }
}
