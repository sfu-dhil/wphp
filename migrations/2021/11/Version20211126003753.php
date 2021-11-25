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
final class Version20211126003753 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE title_title (title_source INT NOT NULL, title_target INT NOT NULL, INDEX IDX_EBC7CE1C912B6A5A (title_source), INDEX IDX_EBC7CE1C88CE3AD5 (title_target), PRIMARY KEY(title_source, title_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE title_title ADD CONSTRAINT FK_EBC7CE1C912B6A5A FOREIGN KEY (title_source) REFERENCES title (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE title_title ADD CONSTRAINT FK_EBC7CE1C88CE3AD5 FOREIGN KEY (title_target) REFERENCES title (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO title_title (title_source, title_target) SELECT source_title_id, related_title_id FROM related_title');

        $this->addSql('ALTER TABLE related_title DROP FOREIGN KEY FK_5BB79E40FC767AC1');
        $this->addSql('DROP TABLE related_title');
        $this->addSql('DROP TABLE title_relationship');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
