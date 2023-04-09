<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409161029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB94A4C7D4');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE location RENAME INDEX fk_5e9e89cb94a4c7d4 TO IDX_5E9E89CB94A4C7D4');
        $this->addSql('ALTER TABLE device CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE device CHANGE guid guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE device CHANGE disabled disabled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE device CHANGE id id INT NOT NULL, CHANGE guid guid VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB94A4C7D4');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location RENAME INDEX idx_5e9e89cb94a4c7d4 TO FK_5E9E89CB94A4C7D4');
        $this->addSql('ALTER TABLE device CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE device CHANGE guid guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE device CHANGE disabled disabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE device CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE guid guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }
}
