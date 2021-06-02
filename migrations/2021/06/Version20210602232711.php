<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602232711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE firm_source (id INT AUTO_INCREMENT NOT NULL, firm_id INT DEFAULT NULL, source_id INT DEFAULT NULL, identifier VARCHAR(300) DEFAULT NULL, INDEX IDX_507ADB9A89AF7860 (firm_id), INDEX IDX_507ADB9A953C1C61 (source_id), INDEX firm_source_identifier_idx (identifier), FULLTEXT INDEX firm_source_identifier_ft (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE firm_source ADD CONSTRAINT FK_507ADB9A89AF7860 FOREIGN KEY (firm_id) REFERENCES firm (id)');
        $this->addSql('ALTER TABLE firm_source ADD CONSTRAINT FK_507ADB9A953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
