<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111014146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX firm_uniq ON firm');
        $this->addSql('DROP INDEX titlerole_uniq ON title_role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX firm_uniq ON firm (name, city_id, start_date, end_date)');
        $this->addSql('CREATE UNIQUE INDEX titlerole_uniq ON title_role (title_id, person_id, role_id)');
    }
}
