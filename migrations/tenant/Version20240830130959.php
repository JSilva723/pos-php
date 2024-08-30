<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830130959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'remos isDarkTheme column of user table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($schema->getTable('user')->hasColumn('is_dark_theme') === false, 'The column not exist.');
        $this->addSql('ALTER TABLE user DROP COLUMN is_dark_theme');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_dark_theme TINYINT(1) NOT NULL DEFAULT 1');
    }
}
