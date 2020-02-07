<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Clean up a bunch of tables.
 */
final class Version20190502191225 extends AbstractMigration {
    /**
     * Apply the migration.
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flag_entity DROP FOREIGN KEY FK_E37C49B9919FE4E5');
        $this->addSql('DROP TABLE flag');
        $this->addSql('DROP TABLE flag_entity');
        $this->addSql('DROP TABLE orlando_person');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE relation');
        $this->addSql('ALTER TABLE title CHANGE price_total price_total INT NOT NULL');
        $this->addSql('CREATE FULLTEXT INDEX title_notes_idx ON title (notes)');
        $this->addSql('DROP INDEX estcmarc_data_idx ON estc_fields');
        $this->addSql('CREATE INDEX estcmarc_data_idx ON estc_fields (field_data(24))');
        $this->addSql('ALTER TABLE marc_tag_structure DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE marc_tag_structure ADD id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, ADD hidden INT DEFAULT NULL, DROP frameworkcode, DROP libopac, DROP repeatable, DROP mandatory, DROP authorised_value, CHANGE tagfield tagfield VARCHAR(3) NOT NULL, CHANGE liblibrarian liblibrarian VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX marctag_tagfield_uniq ON marc_tag_structure (tagfield)');
        $this->addSql('DROP INDEX osborne_data_idx ON osborne_marc');
        $this->addSql('CREATE INDEX osborne_data_idx ON osborne_marc (field_data(24))');
        $this->addSql('ALTER TABLE title_firmrole DROP FOREIGN KEY FK_1576808289AF7860');
        $this->addSql('ALTER TABLE title_firmrole DROP FOREIGN KEY FK_15768082A9F87BD');
        $this->addSql('DROP INDEX title_id ON title_firmrole');
        $this->addSql('ALTER TABLE title_firmrole ADD CONSTRAINT FK_1576808289AF7860 FOREIGN KEY (firm_id) REFERENCES firm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE title_firmrole ADD CONSTRAINT FK_15768082A9F87BD FOREIGN KEY (title_id) REFERENCES title (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX firm_uniq ON firm');
        $this->addSql('CREATE UNIQUE INDEX firm_uniq ON firm (name, city_id, start_date, end_date)');
        $this->addSql('DROP INDEX geonames_search_idx ON geonames');
        $this->addSql('CREATE INDEX geonames_search_idx ON geonames (name, geonameid, country)');
    }

    /**
     * This migration cannot be undone.
     *
     * @throws \Doctrine\DBAL\Migrations\IrreversibleMigrationException
     */
    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
