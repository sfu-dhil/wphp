<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513163537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE feedback');
        $this->addSql('ALTER TABLE currency CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE en CHANGE further_editions further_editions LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE firm CHANGE street_address street_address LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE firmrole CHANGE name name LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE format CHANGE name name LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE jackson_biblio CHANGE pubdate pubdate LONGTEXT DEFAULT NULL, CHANGE comment comment LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_F4DA3AB0A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE in_menu in_menu TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX idx_f4da3ab0a76ed395 ON nines_blog_page');
        $this->addSql('CREATE INDEX IDX_23FD24C7A76ED395 ON nines_blog_page (user_id)');
        $this->addSql('DROP INDEX blog_page_content ON nines_blog_page');
        $this->addSql('CREATE FULLTEXT INDEX blog_page_ft ON nines_blog_page (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_F4DA3AB0A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_ba5ae01d12469de2 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6A12469DE2 ON nines_blog_post (category_id)');
        $this->addSql('DROP INDEX idx_ba5ae01d6bf700bd ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6A6BF700BD ON nines_blog_post (status_id)');
        $this->addSql('DROP INDEX idx_ba5ae01da76ed395 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6AA76ED395 ON nines_blog_post (user_id)');
        $this->addSql('DROP INDEX blog_post_content ON nines_blog_post');
        $this->addSql('CREATE FULLTEXT INDEX blog_post_ft ON nines_blog_post (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id)');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(200) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL, CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_ca275a0cea750e8 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8CEA750E8 ON nines_blog_post_category (label)');
        $this->addSql('DROP INDEX idx_ca275a0c6de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8C6DE44026 ON nines_blog_post_category (description)');
        $this->addSql('DROP INDEX idx_ca275a0cea750e86de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8CEA750E86DE44026 ON nines_blog_post_category (label, description)');
        $this->addSql('DROP INDEX uniq_ca275a0c5e237e06 ON nines_blog_post_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32F5FC8C5E237E06 ON nines_blog_post_category (name)');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(200) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL, CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_92121d87ea750e8 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FDEA750E8 ON nines_blog_post_status (label)');
        $this->addSql('DROP INDEX idx_92121d876de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FD6DE44026 ON nines_blog_post_status (description)');
        $this->addSql('DROP INDEX idx_92121d87ea750e86de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FDEA750E86DE44026 ON nines_blog_post_status (label, description)');
        $this->addSql('DROP INDEX uniq_92121d875e237e06 ON nines_blog_post_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A63E2FD5E237E06 ON nines_blog_post_status (name)');
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_9474526C6BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_9474526c6bf700bd ON nines_feedback_comment');
        $this->addSql('CREATE INDEX IDX_DD5C8DB56BF700BD ON nines_feedback_comment (status_id)');
        $this->addSql('DROP INDEX comment_ft_idx ON nines_feedback_comment');
        $this->addSql('CREATE FULLTEXT INDEX comment_ft ON nines_feedback_comment (fullname, content)');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_9474526C6BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8A76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_e98b58f8a76ed395 ON nines_feedback_comment_note');
        $this->addSql('CREATE INDEX IDX_4BC0F0BA76ED395 ON nines_feedback_comment_note (user_id)');
        $this->addSql('DROP INDEX idx_e98b58f8f8697d13 ON nines_feedback_comment_note');
        $this->addSql('CREATE INDEX IDX_4BC0F0BF8697D13 ON nines_feedback_comment_note (comment_id)');
        $this->addSql('DROP INDEX commentnote_ft_idx ON nines_feedback_comment_note');
        $this->addSql('CREATE FULLTEXT INDEX comment_note_ft ON nines_feedback_comment_note (content)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8F8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_status CHANGE name name VARCHAR(200) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL, CHANGE created created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX idx_b1133d0eea750e8 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_7B8DA610EA750E8 ON nines_feedback_comment_status (label)');
        $this->addSql('DROP INDEX idx_b1133d0e6de44026 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_7B8DA6106DE44026 ON nines_feedback_comment_status (description)');
        $this->addSql('DROP INDEX idx_b1133d0eea750e86de44026 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_7B8DA610EA750E86DE44026 ON nines_feedback_comment_status (label, description)');
        $this->addSql('DROP INDEX uniq_b1133d0e5e237e06 ON nines_feedback_comment_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B8DA6105E237E06 ON nines_feedback_comment_status (name)');
        $this->addSql('ALTER TABLE orlando_biblio CHANGE AUTHOR AUTHOR LONGTEXT DEFAULT NULL, CHANGE EDITOR EDITOR LONGTEXT DEFAULT NULL, CHANGE INTRODUCTION INTRODUCTION LONGTEXT DEFAULT NULL, CHANGE TRANSLATOR TRANSLATOR LONGTEXT DEFAULT NULL, CHANGE ILLUSTRATOR ILLUSTRATOR LONGTEXT DEFAULT NULL, CHANGE COMPILER COMPILER LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE name name LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source CHANGE description description LONGTEXT DEFAULT NULL, CHANGE citation citation LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE title CHANGE signed_author signed_author LONGTEXT DEFAULT NULL, CHANGE imprint imprint LONGTEXT DEFAULT NULL, CHANGE shelfmark shelfmark LONGTEXT DEFAULT NULL, CHANGE notes notes LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE currency CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE en CHANGE further_editions further_editions TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE firm CHANGE street_address street_address MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE firmrole CHANGE name name MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE format CHANGE name name MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE jackson_biblio CHANGE pubdate pubdate MEDIUMTEXT DEFAULT NULL, CHANGE comment comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_23FD24C7A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page CHANGE in_menu in_menu TINYINT(1) DEFAULT 1 NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_23fd24c7a76ed395 ON nines_blog_page');
        $this->addSql('CREATE INDEX IDX_F4DA3AB0A76ED395 ON nines_blog_page (user_id)');
        $this->addSql('DROP INDEX blog_page_ft ON nines_blog_page');
        $this->addSql('CREATE FULLTEXT INDEX blog_page_content ON nines_blog_page (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_23FD24C7A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6AA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_6d7dfe6aa76ed395 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DA76ED395 ON nines_blog_post (user_id)');
        $this->addSql('DROP INDEX blog_post_ft ON nines_blog_post');
        $this->addSql('CREATE FULLTEXT INDEX blog_post_content ON nines_blog_post (title, searchable)');
        $this->addSql('DROP INDEX idx_6d7dfe6a12469de2 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D12469DE2 ON nines_blog_post (category_id)');
        $this->addSql('DROP INDEX idx_6d7dfe6a6bf700bd ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D6BF700BD ON nines_blog_post (status_id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6AA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_32f5fc8c6de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0C6DE44026 ON nines_blog_post_category (description)');
        $this->addSql('DROP INDEX idx_32f5fc8cea750e86de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0CEA750E86DE44026 ON nines_blog_post_category (label, description)');
        $this->addSql('DROP INDEX uniq_32f5fc8c5e237e06 ON nines_blog_post_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA275A0C5E237E06 ON nines_blog_post_category (name)');
        $this->addSql('DROP INDEX idx_32f5fc8cea750e8 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0CEA750E8 ON nines_blog_post_category (label)');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_4a63e2fd6de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D876DE44026 ON nines_blog_post_status (description)');
        $this->addSql('DROP INDEX idx_4a63e2fdea750e86de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D87EA750E86DE44026 ON nines_blog_post_status (label, description)');
        $this->addSql('DROP INDEX uniq_4a63e2fd5e237e06 ON nines_blog_post_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92121D875E237E06 ON nines_blog_post_status (name)');
        $this->addSql('DROP INDEX idx_4a63e2fdea750e8 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D87EA750E8 ON nines_blog_post_status (label)');
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_DD5C8DB56BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_dd5c8db56bf700bd ON nines_feedback_comment');
        $this->addSql('CREATE INDEX IDX_9474526C6BF700BD ON nines_feedback_comment (status_id)');
        $this->addSql('DROP INDEX comment_ft ON nines_feedback_comment');
        $this->addSql('CREATE FULLTEXT INDEX comment_ft_idx ON nines_feedback_comment (fullname, content)');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_DD5C8DB56BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BA76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BF8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX comment_note_ft ON nines_feedback_comment_note');
        $this->addSql('CREATE FULLTEXT INDEX commentnote_ft_idx ON nines_feedback_comment_note (content)');
        $this->addSql('DROP INDEX idx_4bc0f0ba76ed395 ON nines_feedback_comment_note');
        $this->addSql('CREATE INDEX IDX_E98B58F8A76ED395 ON nines_feedback_comment_note (user_id)');
        $this->addSql('DROP INDEX idx_4bc0f0bf8697d13 ON nines_feedback_comment_note');
        $this->addSql('CREATE INDEX IDX_E98B58F8F8697D13 ON nines_feedback_comment_note (comment_id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BF8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
        $this->addSql('DROP INDEX idx_7b8da6106de44026 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_B1133D0E6DE44026 ON nines_feedback_comment_status (description)');
        $this->addSql('DROP INDEX idx_7b8da610ea750e86de44026 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_B1133D0EEA750E86DE44026 ON nines_feedback_comment_status (label, description)');
        $this->addSql('DROP INDEX uniq_7b8da6105e237e06 ON nines_feedback_comment_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1133D0E5E237E06 ON nines_feedback_comment_status (name)');
        $this->addSql('DROP INDEX idx_7b8da610ea750e8 ON nines_feedback_comment_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_B1133D0EEA750E8 ON nines_feedback_comment_status (label)');
        $this->addSql('ALTER TABLE orlando_biblio CHANGE AUTHOR AUTHOR TEXT DEFAULT NULL, CHANGE EDITOR EDITOR TEXT DEFAULT NULL, CHANGE INTRODUCTION INTRODUCTION TEXT DEFAULT NULL, CHANGE TRANSLATOR TRANSLATOR TEXT DEFAULT NULL, CHANGE ILLUSTRATOR ILLUSTRATOR TEXT DEFAULT NULL, CHANGE COMPILER COMPILER TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE name name MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source CHANGE description description MEDIUMTEXT DEFAULT NULL, CHANGE citation citation MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE title CHANGE signed_author signed_author MEDIUMTEXT DEFAULT NULL, CHANGE imprint imprint MEDIUMTEXT DEFAULT NULL, CHANGE shelfmark shelfmark MEDIUMTEXT DEFAULT NULL, CHANGE notes notes MEDIUMTEXT DEFAULT NULL');
    }
}
