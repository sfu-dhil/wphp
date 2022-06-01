<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601174953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nines_media_audio (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(128) NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(64) NOT NULL, file_size INT NOT NULL, FULLTEXT INDEX nines_media_audio_ft (original_name, description), INDEX IDX_9D15F751E284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nines_media_image (id INT AUTO_INCREMENT NOT NULL, thumb_path VARCHAR(128) NOT NULL, image_width INT NOT NULL, image_height INT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(128) NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(64) NOT NULL, file_size INT NOT NULL, FULLTEXT INDEX nines_media_image_ft (original_name, description), INDEX IDX_4055C59BE284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nines_media_link (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(500) NOT NULL, text VARCHAR(191) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(120) NOT NULL, FULLTEXT INDEX nines_media_link_ft (url, text), INDEX IDX_3B5D85A3E284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nines_media_pdf (id INT AUTO_INCREMENT NOT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(128) NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(64) NOT NULL, file_size INT NOT NULL, thumb_path VARCHAR(128) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, entity VARCHAR(120) NOT NULL, FULLTEXT INDEX nines_media_pdf_ft (original_name, description), INDEX IDX_9286B706E284468 (entity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE nines_media_audio');
        $this->addSql('DROP TABLE nines_media_image');
        $this->addSql('DROP TABLE nines_media_link');
        $this->addSql('DROP TABLE nines_media_pdf');
    }
}
