<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502231256 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE person SET gender = \'U\' WHERE gender is null');
        $this->addSql('UPDATE firm SET gender = \'U\' WHERE gender is null');

        $this->addSql('ALTER TABLE person CHANGE gender gender VARCHAR(1) DEFAULT \'U\' NOT NULL');
        $this->addSql('ALTER TABLE firm CHANGE gender gender VARCHAR(1) DEFAULT \'U\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE firm CHANGE gender gender VARCHAR(1) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE person CHANGE gender gender VARCHAR(1) DEFAULT NULL COLLATE utf8mb4_unicode_ci');

        $this->addSql('UPDATE person SET gender = null WHERE gender=\'U\'');
        $this->addSql('UPDATE firm SET gender = null WHERE gender=\'U\'');
    }
}
