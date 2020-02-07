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
 * Fix some defaults by setting gender to Gender::UNKNOWN where it is null. Search forms wouldn't work without this.
 */
final class Version20190502231256 extends AbstractMigration {
    /**
     * Apply the migration.
     */
    public function up(Schema $schema) : void {
        $this->addSql('UPDATE person SET gender = \'U\' WHERE gender is null');
        $this->addSql('UPDATE firm SET gender = \'U\' WHERE gender is null');

        $this->addSql('ALTER TABLE person CHANGE gender gender VARCHAR(1) DEFAULT \'U\' NOT NULL');
        $this->addSql('ALTER TABLE firm CHANGE gender gender VARCHAR(1) DEFAULT \'U\' NOT NULL');
    }

    /**
     * Undo the migration. Does nothing.
     */
    public function down(Schema $schema) : void {
    }
}
