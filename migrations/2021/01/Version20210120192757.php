<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120192757 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE title_role DROP FOREIGN KEY FK_1CB35015217BBB47');
        $this->addSql('ALTER TABLE title_role DROP FOREIGN KEY FK_1CB35015A9F87BD');
        $this->addSql('ALTER TABLE title_role CHANGE title_id title_id INT NOT NULL, CHANGE role_id role_id INT NOT NULL, CHANGE person_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE title_role ADD CONSTRAINT FK_1CB35015217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE title_role ADD CONSTRAINT FK_1CB35015A9F87BD FOREIGN KEY (title_id) REFERENCES title (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE title_role DROP FOREIGN KEY FK_1CB35015A9F87BD');
        $this->addSql('ALTER TABLE title_role DROP FOREIGN KEY FK_1CB35015217BBB47');
        $this->addSql('ALTER TABLE title_role CHANGE title_id title_id INT DEFAULT NULL, CHANGE person_id person_id INT DEFAULT NULL, CHANGE role_id role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE title_role ADD CONSTRAINT FK_1CB35015A9F87BD FOREIGN KEY (title_id) REFERENCES title (id)');
        $this->addSql('ALTER TABLE title_role ADD CONSTRAINT FK_1CB35015217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
    }
}
