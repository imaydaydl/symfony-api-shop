<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226101139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, short_name VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries_vat (id INT AUTO_INCREMENT NOT NULL, country INT DEFAULT NULL, product INT DEFAULT NULL, vat INT NOT NULL, INDEX IDX_F51324385373C966 (country), INDEX IDX_F5132438D34A04AD (product), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price DECIMAL(10,2) NOT NULL, currency VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role INT DEFAULT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(30), email VARCHAR(50) NOT NULL, password VARCHAR(32) NOT NULL, status SMALLINT DEFAULT 1 NOT NULL, api_token VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), INDEX IDX_8D93D64957698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_role (id INT AUTO_INCREMENT NOT NULL, tech_name VARCHAR(20) NOT NULL, role_name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE countries_vat ADD CONSTRAINT FK_F51324385373C966 FOREIGN KEY (country) REFERENCES countries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE countries_vat ADD CONSTRAINT FK_F5132438D34A04AD FOREIGN KEY (product) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_8D93D64957698A6A FOREIGN KEY (role) REFERENCES users_role (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE countries_vat DROP FOREIGN KEY FK_F51324385373C966');
        $this->addSql('ALTER TABLE countries_vat DROP FOREIGN KEY FK_F5132438D34A04AD');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_8D93D64957698A6A');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE countries_vat');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_role');
    }
}
