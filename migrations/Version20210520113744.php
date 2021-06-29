<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520113744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, percentage DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount_product (id INT AUTO_INCREMENT NOT NULL, discount_id INT DEFAULT NULL, product_id INT DEFAULT NULL, INDEX IDX_654269BC4C7C611F (discount_id), INDEX IDX_654269BC4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, discount_code_id INT DEFAULT NULL, customer_id INT NOT NULL, total DOUBLE PRECISION DEFAULT NULL, date DATETIME NOT NULL, discount_used TINYINT(1) NOT NULL, INDEX IDX_F529939891D29306 (discount_code_id), INDEX IDX_F52993989395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pdf (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE row (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order2_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_8430F6DB4584665A (product_id), INDEX IDX_8430F6DBEC56A58E (order2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE discount_product ADD CONSTRAINT FK_654269BC4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE discount_product ADD CONSTRAINT FK_654269BC4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939891D29306 FOREIGN KEY (discount_code_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE row ADD CONSTRAINT FK_8430F6DB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE row ADD CONSTRAINT FK_8430F6DBEC56A58E FOREIGN KEY (order2_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE discount_product DROP FOREIGN KEY FK_654269BC4C7C611F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939891D29306');
        $this->addSql('ALTER TABLE row DROP FOREIGN KEY FK_8430F6DBEC56A58E');
        $this->addSql('ALTER TABLE discount_product DROP FOREIGN KEY FK_654269BC4584665A');
        $this->addSql('ALTER TABLE row DROP FOREIGN KEY FK_8430F6DB4584665A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE discount_product');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE pdf');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE row');
    }
}
