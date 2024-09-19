<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919065621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'refactor move mappin to xml';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (name VARCHAR(100) NOT NULL, isEnable TINYINT(1) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (name VARCHAR(100) NOT NULL, isEnable TINYINT(1) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE payment (name VARCHAR(100) NOT NULL, isEnable TINYINT(1) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE price_list (name VARCHAR(100) NOT NULL, isEnable TINYINT(1) NOT NULL, id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (name VARCHAR(100) NOT NULL, brand VARCHAR(100) DEFAULT NULL, isEnable TINYINT(1) NOT NULL, sku VARCHAR(100) DEFAULT NULL, stockQuantity INT NOT NULL, stockMin INT NOT NULL, img VARCHAR(255) DEFAULT NULL, mimeType VARCHAR(255) DEFAULT NULL, id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), UNIQUE INDEX UNIQ_SKU (sku), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_price_list (price NUMERIC(14, 2) NOT NULL, id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, price_list_id INT DEFAULT NULL, INDEX IDX_AAFD55894584665A (product_id), INDEX IDX_AAFD55895688DED7 (price_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sale_order (status VARCHAR(10) NOT NULL, date DATETIME NOT NULL, id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, client_id INT DEFAULT NULL, price_list_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, INDEX IDX_25F5CB1BA76ED395 (user_id), INDEX IDX_25F5CB1B19EB6921 (client_id), INDEX IDX_25F5CB1B5688DED7 (price_list_id), INDEX IDX_25F5CB1B4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sale_order_line (quantity NUMERIC(10, 2) NOT NULL, price NUMERIC(14, 2) NOT NULL, id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, sale_order_id INT DEFAULT NULL, INDEX IDX_61B16AA54584665A (product_id), INDEX IDX_61B16AA593EB8192 (sale_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, isEnable TINYINT(1) NOT NULL, id INT AUTO_INCREMENT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_price_list ADD CONSTRAINT FK_AAFD55894584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_price_list ADD CONSTRAINT FK_AAFD55895688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE sale_order_line ADD CONSTRAINT FK_61B16AA54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sale_order_line ADD CONSTRAINT FK_61B16AA593EB8192 FOREIGN KEY (sale_order_id) REFERENCES sale_order (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_price_list DROP FOREIGN KEY FK_AAFD55894584665A');
        $this->addSql('ALTER TABLE product_price_list DROP FOREIGN KEY FK_AAFD55895688DED7');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1BA76ED395');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1B19EB6921');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1B5688DED7');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1B4C3A3BB');
        $this->addSql('ALTER TABLE sale_order_line DROP FOREIGN KEY FK_61B16AA54584665A');
        $this->addSql('ALTER TABLE sale_order_line DROP FOREIGN KEY FK_61B16AA593EB8192');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE price_list');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_price_list');
        $this->addSql('DROP TABLE sale_order');
        $this->addSql('DROP TABLE sale_order_line');
        $this->addSql('DROP TABLE user');
    }
}
