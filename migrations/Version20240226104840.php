<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226104840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $length = 32;
        $token = '';

        while (($len = strlen($token)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $token .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        $this->addSql('INSERT INTO users_role (id, tech_name, role_name) VALUES (1, "ROLE_ADMIN", "ROLE_ADMIN"), (2, "ROLE_USER", "ROLE_USER")');
        $this->addSql('INSERT INTO users (role, name, email, password, status, api_token) VALUES (1, "admin", "test@test.com", "admin54321", 1, "'.$token.'")');
        $this->addSql('INSERT INTO countries (id, name, short_name) VALUES (1, "England", "en"), (2, "France", "fr")');
        $this->addSql('INSERT INTO products (id, name, price, currency) VALUES (1, "Bread", 10, "USD"), (2, "Wine", 50, "USD")');
        $this->addSql('INSERT INTO countries_vat (country, product, vat) VALUES (1, 1, 7), (1, 2, 15), (2, 1, 5), (2, 2, 12)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM users_role');
        $this->addSql('DELETE FROM users');
        $this->addSql('DELETE FROM countries');
        $this->addSql('DELETE FROM products');
        $this->addSql('DELETE FROM countries_vat');
    }
}
