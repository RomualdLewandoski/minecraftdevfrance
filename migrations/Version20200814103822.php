<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814103822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_reply (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, reply_id INT NOT NULL, date DATETIME NOT NULL, reply_content LONGTEXT NOT NULL, INDEX IDX_D7B4C199F675F31B (author_id), INDEX IDX_D7B4C1998A0E4E7F (reply_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_reply ADD CONSTRAINT FK_D7B4C199F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_reply ADD CONSTRAINT FK_D7B4C1998A0E4E7F FOREIGN KEY (reply_id) REFERENCES reply (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE report_reply');
    }
}
