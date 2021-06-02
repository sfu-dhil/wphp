<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602165204 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE title_genre (title_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_43A1858FA9F87BD (title_id), INDEX IDX_43A1858F4296D31F (genre_id), PRIMARY KEY(title_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE title_genre ADD CONSTRAINT FK_43A1858FA9F87BD FOREIGN KEY (title_id) REFERENCES title (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE title_genre ADD CONSTRAINT FK_43A1858F4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO title_genre SELECT id,genre_id FROM title WHERE genre_id IS NOT NULL');
        $this->addSql('ALTER TABLE title DROP FOREIGN KEY FK_2B36786B4296D31F');
        $this->addSql('ALTER TABLE title DROP COLUMN genre_id');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
