<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201003207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE firm ADD created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE person ADD created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE title ADD created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('UPDATE firm SET created=now(), updated=now()');
        $this->addSql('UPDATE person SET created=now(), updated=now()');
        $this->addSql('UPDATE title SET created=now(), updated=now()');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE firm DROP created, DROP updated');
        $this->addSql('ALTER TABLE person DROP created, DROP updated');
        $this->addSql('ALTER TABLE title DROP created, DROP updated');
    }
}
