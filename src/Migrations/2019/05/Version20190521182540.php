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
 * Fix some bad dates and selfpublished attributes.
 */
final class Version20190521182540 extends AbstractMigration {
    /**
     * Apply the migration.
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('begin');
        $this->addSql('UPDATE firm SET start_date=REGEXP_REPLACE(start_date, \'-[0-9]*\', \'\') WHERE start_date IS NOT NULL');
        $this->addSql('UPDATE firm SET end_date=REGEXP_REPLACE(end_date, \'-[0-9]*\', \'\') WHERE end_date IS NOT NULL');
        $this->addSql('ALTER TABLE firm CHANGE start_date start_date VARCHAR(4) DEFAULT NULL, CHANGE end_date end_date VARCHAR(4) DEFAULT NULL');
        $this->addSql('update title set selfpublished=1 where id in (SELECT title_id FROM title_firmrole where firm_id=37 or firm_id=340)');
        $this->addSql('update title set selfpublished=0 where id not in (SELECT title_id FROM title_firmrole where firm_id=37 or firm_id=340)');
        $this->addSql('update title set selfpublished=null where id not in (SELECT distinct title_id from title_firmrole)');
        $this->addSql('delete from title_firmrole where firm_id=37 or firm_id=340');
        $this->addSql('delete from firm where id=37 or id=340');
        $this->addSql('commit');
    }

    /**
     * Undo the migration.
     *
     * @throws \Doctrine\DBAL\Migrations\IrreversibleMigrationException
     */
    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
