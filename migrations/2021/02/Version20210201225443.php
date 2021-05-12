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
final class Version20210201225443 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE firm_firm (firm_source INT NOT NULL, firm_target INT NOT NULL, INDEX IDX_44B8C6D507ADB9A (firm_source), INDEX IDX_44B8C6D499F8B15 (firm_target), PRIMARY KEY(firm_source, firm_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE firm_firm ADD CONSTRAINT FK_44B8C6D507ADB9A FOREIGN KEY (firm_source) REFERENCES firm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE firm_firm ADD CONSTRAINT FK_44B8C6D499F8B15 FOREIGN KEY (firm_target) REFERENCES firm (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE firm_firm');
    }
}
