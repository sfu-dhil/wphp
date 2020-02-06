<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Clean up the volumes attributes on titles.
 */
final class Version20190506214754 extends AbstractMigration {
    /**
     * Apply the migration.
     *
     * @param Schema $schema
     */
    public function up(Schema $schema) : void {
        $this->addSql('UPDATE title SET volumes = null WHERE volumes = 0');
    }

    /**
     * Undo the migration. Does nothing.
     *
     * @param Schema $schema
     */
    public function down(Schema $schema) : void {
        // nothing to do here.
    }
}
