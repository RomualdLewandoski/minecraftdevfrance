<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805133632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE like_reply (id INT AUTO_INCREMENT NOT NULL, reply_id INT NOT NULL, author_id INT NOT NULL, is_like TINYINT(1) NOT NULL, is_dislike TINYINT(1) NOT NULL, INDEX IDX_4ACCEF078A0E4E7F (reply_id), INDEX IDX_4ACCEF07F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE like_reply ADD CONSTRAINT FK_4ACCEF078A0E4E7F FOREIGN KEY (reply_id) REFERENCES reply (id)');
        $this->addSql('ALTER TABLE like_reply ADD CONSTRAINT FK_4ACCEF07F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE like_reply');
    }
}
