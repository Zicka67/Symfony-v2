<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328000155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_flavor (products_id INT NOT NULL, flavor_id INT NOT NULL, INDEX IDX_3A16BF7E6C8A81A9 (products_id), INDEX IDX_3A16BF7EFDDA6450 (flavor_id), PRIMARY KEY(products_id, flavor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products_flavor ADD CONSTRAINT FK_3A16BF7E6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_flavor ADD CONSTRAINT FK_3A16BF7EFDDA6450 FOREIGN KEY (flavor_id) REFERENCES flavor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_flavor DROP FOREIGN KEY FK_3A16BF7E6C8A81A9');
        $this->addSql('ALTER TABLE products_flavor DROP FOREIGN KEY FK_3A16BF7EFDDA6450');
        $this->addSql('DROP TABLE products_flavor');
    }
}
