<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508233241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fos_user');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD student_id INT DEFAULT NULL, ADD form_control_id INT DEFAULT NULL, ADD mark VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B2CB944F1A FOREIGN KEY (student_id) REFERENCES journal_student (id)');
        $this->addSql('ALTER TABLE journal_form_control_mark ADD CONSTRAINT FK_191930B23B798B9A FOREIGN KEY (form_control_id) REFERENCES journal_form_control (id)');
        $this->addSql('CREATE INDEX IDX_191930B2CB944F1A ON journal_form_control_mark (student_id)');
        $this->addSql('CREATE INDEX IDX_191930B23B798B9A ON journal_form_control_mark (form_control_id)');
        $this->addSql('ALTER TABLE journal_form_control ADD type_form_control_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE journal_form_control ADD CONSTRAINT FK_F7BFF3FCC4D1FEE FOREIGN KEY (type_form_control_id) REFERENCES journal_type_form_control (id)');
        $this->addSql('CREATE INDEX IDX_F7BFF3FCC4D1FEE ON journal_form_control (type_form_control_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE journal_form_control DROP FOREIGN KEY FK_F7BFF3FCC4D1FEE');
        $this->addSql('DROP INDEX IDX_F7BFF3FCC4D1FEE ON journal_form_control');
        $this->addSql('ALTER TABLE journal_form_control DROP type_form_control_id, DROP name, DROP date');
        $this->addSql('ALTER TABLE journal_form_control_mark DROP FOREIGN KEY FK_191930B2CB944F1A');
        $this->addSql('ALTER TABLE journal_form_control_mark DROP FOREIGN KEY FK_191930B23B798B9A');
        $this->addSql('DROP INDEX IDX_191930B2CB944F1A ON journal_form_control_mark');
        $this->addSql('DROP INDEX IDX_191930B23B798B9A ON journal_form_control_mark');
        $this->addSql('ALTER TABLE journal_form_control_mark DROP student_id, DROP form_control_id, DROP mark');
    }
}
