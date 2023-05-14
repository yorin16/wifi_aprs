<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514104434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'removed answer_options table, integrated into question';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer_options DROP FOREIGN KEY FK_987535F61E27F6BF');
        $this->addSql('DROP TABLE answer_options');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer_options (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, text VARCHAR(1024) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, correct TINYINT(1) DEFAULT NULL, INDEX IDX_987535F61E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE answer_options ADD CONSTRAINT FK_987535F61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
