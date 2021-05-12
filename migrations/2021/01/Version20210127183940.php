<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127183940 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE related_title (id INT AUTO_INCREMENT NOT NULL, source_title_id INT NOT NULL, related_title_id INT NOT NULL, title_relationship_id INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_5BB79E4049BFB16F (source_title_id), INDEX IDX_5BB79E40AC1CC910 (related_title_id), INDEX IDX_5BB79E40FC767AC1 (title_relationship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE title_relationship (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, label VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, FULLTEXT INDEX IDX_C641CD86EA750E8 (label), FULLTEXT INDEX IDX_C641CD866DE44026 (description), FULLTEXT INDEX IDX_C641CD86EA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_C641CD865E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE related_title ADD CONSTRAINT FK_5BB79E4049BFB16F FOREIGN KEY (source_title_id) REFERENCES title (id)');
        $this->addSql('ALTER TABLE related_title ADD CONSTRAINT FK_5BB79E40AC1CC910 FOREIGN KEY (related_title_id) REFERENCES title (id)');
        $this->addSql('ALTER TABLE related_title ADD CONSTRAINT FK_5BB79E40FC767AC1 FOREIGN KEY (title_relationship_id) REFERENCES title_relationship (id)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE related_title DROP FOREIGN KEY FK_5BB79E40FC767AC1');
        $this->addSql('DROP TABLE related_title');
        $this->addSql('DROP TABLE title_relationship');
    }
}
