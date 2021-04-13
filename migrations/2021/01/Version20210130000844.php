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
final class Version20210130000844 extends AbstractMigration
{
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_firm (person_id INT NOT NULL, firm_id INT NOT NULL, INDEX IDX_DCE305D6217BBB47 (person_id), INDEX IDX_DCE305D689AF7860 (firm_id), PRIMARY KEY(person_id, firm_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_firm ADD CONSTRAINT FK_DCE305D6217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_firm ADD CONSTRAINT FK_DCE305D689AF7860 FOREIGN KEY (firm_id) REFERENCES firm (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE person_firm');
    }
}
