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
final class Version20211116191201 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $sql = <<<'ENDSQL'
                CREATE TABLE aas_fields (
                    id INT AUTO_INCREMENT NOT NULL,
                    cid INT NOT NULL,
                    field VARCHAR(3) NOT NULL,
                    subfield VARCHAR(1) DEFAULT NULL,
                    field_data LONGTEXT NOT NULL,
                    INDEX aasmarc_cid_idx (cid),
                    FULLTEXT INDEX aasmarc_data_ft (field_data),
                    INDEX aasmarc_data_idx (field_data(24)),
                    INDEX aasmarc_field_idx (field),
                    PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            ENDSQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE aas_fields');
    }
}
