<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240824052700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'sale arder table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale_order (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(10) NOT NULL, date DATETIME NOT NULL, payment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_sale_order_payment_id (payment_id), INDEX IDX_sale_order_user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_sale_order_payment_id FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_sale_order_user_id FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_sale_order_payment_id');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_sale_order_user_id');
        $this->addSql('DROP TABLE sale_order');
    }
}
