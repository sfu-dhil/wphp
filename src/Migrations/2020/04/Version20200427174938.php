<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427174938 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        $this->addSql("UPDATE comment SET entity=replace(entity, 'AppBundle', 'App')");
    }

    public function down(Schema $schema) : void {
        $this->addSql("UPDATE comment SET entity=replace(entity, 'App', 'AppBundle')");
    }
}
