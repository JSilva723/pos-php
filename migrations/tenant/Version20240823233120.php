<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823233120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add product';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, is_enable TINYINT(1) NOT NULL DEFAULT 1, sku VARCHAR(100) DEFAULT NULL, price NUMERIC(14, 2) NOT NULL, stock_quantity INT NOT NULL, img LONGTEXT DEFAULT NULL, mime_type VARCHAR(10) DEFAULT NULL, category_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_product_sku (sku), INDEX IDX_product_category_id (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_product_category_id FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_product_category_id');
        $this->addSql('DROP TABLE product');
    }
}
