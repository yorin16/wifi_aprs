<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716110922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer ADD points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_50d0c606a76ed395 TO IDX_DADD4A25A76ED395');
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_50d0c6061e27f6bf TO IDX_DADD4A251E27F6BF');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP points');
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_dadd4a251e27f6bf TO IDX_50D0C6061E27F6BF');
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_dadd4a25a76ed395 TO IDX_50D0C606A76ED395');
    }
}
