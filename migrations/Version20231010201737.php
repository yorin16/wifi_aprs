<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010201737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_dadd4a257a3a4d64 TO IDX_DADD4A25C1D40D30');
        $this->addSql('ALTER TABLE question ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP image');
        $this->addSql('ALTER TABLE answer RENAME INDEX idx_dadd4a25c1d40d30 TO IDX_DADD4A257A3A4D64');
    }
}
