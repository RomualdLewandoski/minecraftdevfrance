<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814103956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_wall (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, wall_id INT NOT NULL, date DATETIME NOT NULL, wall_text LONGTEXT NOT NULL, INDEX IDX_60D9020DF675F31B (author_id), INDEX IDX_60D9020DC33923F1 (wall_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_wall ADD CONSTRAINT FK_60D9020DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_wall ADD CONSTRAINT FK_60D9020DC33923F1 FOREIGN KEY (wall_id) REFERENCES user_wall (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE report_wall');
    }
}
