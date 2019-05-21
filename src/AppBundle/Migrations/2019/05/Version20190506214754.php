<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506214754 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE title SET volumes = null WHERE volumes = 0');
    }

    public function down(Schema $schema) : void
    {
        // nothing to do here.
    }
}
