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
 * Clean up the volumes attributes on titles.
 */
final class Version20190506214754 extends AbstractMigration {
    /**
     * Apply the migration.
     */
    public function up(Schema $schema) : void {
        $this->addSql('UPDATE title SET volumes = null WHERE volumes = 0');
    }

    /**
     * Undo the migration. Does nothing.
     */
    public function down(Schema $schema) : void {
        // nothing to do here.
    }
}
