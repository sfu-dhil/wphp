<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815011527 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_9474526C6BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_DD5C8DB56BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8A76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BF8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_media_audio ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public');
        $this->addSql('CREATE INDEX IDX_9D15F751DE6FDF9A ON nines_media_audio (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_9D15F751A58240EF ON nines_media_audio (source_url)');
        $this->addSql('ALTER TABLE nines_media_image ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public');
        $this->addSql('CREATE INDEX IDX_4055C59BDE6FDF9A ON nines_media_image (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4055C59BA58240EF ON nines_media_image (source_url)');
        $this->addSql('ALTER TABLE nines_media_pdf ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public');
        $this->addSql('CREATE INDEX IDX_9286B706DE6FDF9A ON nines_media_pdf (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_9286B706A58240EF ON nines_media_pdf (source_url)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_DD5C8DB56BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_9474526C6BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BA76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BF8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8F8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id)');
        $this->addSql('DROP INDEX IDX_9D15F751DE6FDF9A ON nines_media_audio');
        $this->addSql('DROP INDEX IDX_9D15F751A58240EF ON nines_media_audio');
        $this->addSql('ALTER TABLE nines_media_audio ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url');
        $this->addSql('DROP INDEX IDX_4055C59BDE6FDF9A ON nines_media_image');
        $this->addSql('DROP INDEX IDX_4055C59BA58240EF ON nines_media_image');
        $this->addSql('ALTER TABLE nines_media_image ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url');
        $this->addSql('DROP INDEX IDX_9286B706DE6FDF9A ON nines_media_pdf');
        $this->addSql('DROP INDEX IDX_9286B706A58240EF ON nines_media_pdf');
        $this->addSql('ALTER TABLE nines_media_pdf ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url');
    }
}
