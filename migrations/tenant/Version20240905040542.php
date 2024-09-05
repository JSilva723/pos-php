<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905040542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add client table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, is_enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE sale_order ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_25F5CB1B19EB6921 ON sale_order (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE sale_order DROP FOREIGN KEY FK_25F5CB1B19EB6921');
        $this->addSql('DROP INDEX IDX_25F5CB1B19EB6921 ON sale_order');
        $this->addSql('ALTER TABLE sale_order DROP client_id');
    }
}
