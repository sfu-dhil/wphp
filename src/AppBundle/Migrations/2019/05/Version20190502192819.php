<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Move title sources from the title table to their own title_source table.
 */
final class Version20190502192819 extends AbstractMigration
{

    /**
     * Apply the migration.
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE title_source (id INT AUTO_INCREMENT NOT NULL, title_id INT DEFAULT NULL, source_id INT DEFAULT NULL, identifier VARCHAR(300) DEFAULT NULL, INDEX IDX_912B6A5AA9F87BD (title_id), INDEX IDX_912B6A5A953C1C61 (source_id), INDEX title_source_identifier_idx (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE title_source ADD CONSTRAINT FK_912B6A5AA9F87BD FOREIGN KEY (title_id) REFERENCES title (id)');
        $this->addSql('ALTER TABLE title_source ADD CONSTRAINT FK_912B6A5A953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');


        $this->addSql('INSERT INTO title_source(title_id, source_id, identifier) SELECT id, source, source_id FROM title WHERE source IS NOT NULL');
        $this->addSql('INSERT INTO title_source(title_id, source_id, identifier) SELECT id, source2, source2_id FROM title WHERE source2 IS NOT NULL');
        $this->addSql('INSERT INTO title_source(title_id, source_id, identifier) SELECT id, source3, source3_id FROM title WHERE source3 IS NOT NULL');

        $this->addSql('ALTER TABLE title DROP FOREIGN KEY FK_2B36786B5F8A7F73');
        $this->addSql('ALTER TABLE title DROP FOREIGN KEY FK_2B36786BA4812462');
        $this->addSql('ALTER TABLE title DROP FOREIGN KEY FK_2B36786BD38614F4');
        $this->addSql('DROP INDEX IDX_2B36786B5F8A7F73 ON title');
        $this->addSql('DROP INDEX IDX_2B36786BA4812462 ON title');
        $this->addSql('DROP INDEX IDX_2B36786BD38614F4 ON title');

        $this->addSql('ALTER TABLE title DROP source, DROP source2, DROP source3, DROP source_id, DROP source2_id, DROP source3_id');

        $this->addSql('ALTER TABLE firm ADD gender VARCHAR(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE format RENAME COLUMN abbrev1 TO abbreviation, DROP abbrev2, DROP abbrev3, DROP abbrev4');
    }

    /**
     * This migration cannot be undone.
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Migrations\IrreversibleMigrationException
     */
    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
