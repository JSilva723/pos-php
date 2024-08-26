<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823160013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add is_enable column';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD is_enable TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE payment ADD is_enable TINYINT(1) NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE user ADD is_enable TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP is_enable');
        $this->addSql('ALTER TABLE payment DROP is_enable');
        $this->addSql('ALTER TABLE user DROP is_enable');
    }
}
