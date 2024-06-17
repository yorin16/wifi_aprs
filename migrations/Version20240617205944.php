<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617205944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD last_opened_question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498B9473D8 FOREIGN KEY (last_opened_question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498B9473D8 ON user (last_opened_question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6498B9473D8');
        $this->addSql('DROP INDEX IDX_8D93D6498B9473D8 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP last_opened_question_id');
    }
}
