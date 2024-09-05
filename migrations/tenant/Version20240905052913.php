<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905052913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add price list column in sale order';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale_order ADD price_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('CREATE INDEX IDX_25F5CB1B5688DED7 ON sale_order (price_list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1B5688DED7');
        $this->addSql('DROP INDEX IDX_25F5CB1B5688DED7 ON sale_order');
        $this->addSql('ALTER TABLE sale_order DROP price_list_id');
    }
}
