<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805195846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic ADD solution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B1C0BE183 FOREIGN KEY (solution_id) REFERENCES reply (id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B1C0BE183 ON topic (solution_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B1C0BE183');
        $this->addSql('DROP INDEX IDX_9D40DE1B1C0BE183 ON topic');
        $this->addSql('ALTER TABLE topic DROP solution_id');
    }
}
