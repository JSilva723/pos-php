<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830140922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add product_price_list table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_price_list (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(14, 2) NOT NULL, product_id INT NOT NULL, price_list_id INT NOT NULL, INDEX IDX_product_price_list_product_id (product_id), INDEX IDX_product_price_list_price_list_id (price_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product_price_list ADD CONSTRAINT FK_product_price_list_product_id FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_price_list ADD CONSTRAINT FK_product_price_list_price_list_id FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_price_list DROP FOREIGN KEY FK_product_price_list_product_id');
        $this->addSql('ALTER TABLE product_price_list DROP FOREIGN KEY FK_product_price_list');
        $this->addSql('DROP TABLE product_price_list');
    }
}
