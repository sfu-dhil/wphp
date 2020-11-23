<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006214858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) DEFAULT NULL, name VARCHAR(64) NOT NULL, symbol VARCHAR(4) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE title ADD other_currency_id INT DEFAULT NULL, ADD other_price NUMERIC(7, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE title ADD CONSTRAINT FK_2B36786B35F23EF0 FOREIGN KEY (other_currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_2B36786B35F23EF0 ON title (other_currency_id)');
    }

    public function postUp(Schema $schema) : void {
        $this->connection->executeQuery("INSERT INTO currency(code, name, symbol, created, updated) VALUES ('CAD', 'Canadian Dollar', '$', now(), now())");
        $this->connection->executeQuery("INSERT INTO currency(code, name, symbol, created, updated) VALUES ('USD', 'American Dollar', '$', now(), now())");
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
