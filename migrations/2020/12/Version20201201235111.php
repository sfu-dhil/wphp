<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201235111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback CHANGE created created DATETIME NOT NULL');
        $this->addSql('DROP INDEX firm_uniq ON firm');
        $this->addSql('CREATE UNIQUE INDEX firm_uniq ON firm (name, city_id, start_date, end_date)');
        $this->addSql('DROP INDEX geonames_search_idx ON geonames');
        $this->addSql('CREATE INDEX geonames_search_idx ON geonames (name, geonameid, country)');
        $this->addSql('ALTER TABLE nines_user CHANGE active active TINYINT(1) NOT NULL, CHANGE login login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL, CHANGE reset_expiry reset_expiry DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE affiliation affiliation VARCHAR(64) NOT NULL, CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX title_source_identifier_idx ON title_source');
        $this->addSql('CREATE INDEX title_source_identifier_idx ON title_source (identifier)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX firm_uniq ON firm');
        $this->addSql('CREATE UNIQUE INDEX firm_uniq ON firm (name(100), city_id, start_date, end_date)');
        $this->addSql('DROP INDEX geonames_search_idx ON geonames');
        $this->addSql('CREATE INDEX geonames_search_idx ON geonames (name(191), geonameid, country)');
        $this->addSql('ALTER TABLE nines_user CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE reset_token reset_token VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reset_expiry reset_expiry DATETIME DEFAULT NULL, CHANGE affiliation affiliation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE login login DATETIME DEFAULT NULL, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX title_source_identifier_idx ON title_source');
        $this->addSql('CREATE INDEX title_source_identifier_idx ON title_source (identifier(191))');
    }
}
