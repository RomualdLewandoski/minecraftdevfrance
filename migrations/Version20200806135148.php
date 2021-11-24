<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806135148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, target_id_topic_id INT DEFAULT NULL, target_id_reply_id INT DEFAULT NULL, target_id_wall_id INT DEFAULT NULL, date DATETIME NOT NULL, type INT NOT NULL, INDEX IDX_AC74095AF675F31B (author_id), INDEX IDX_AC74095AC8B483A2 (target_id_topic_id), INDEX IDX_AC74095A5DEFEDE0 (target_id_reply_id), INDEX IDX_AC74095A2F485210 (target_id_wall_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AC8B483A2 FOREIGN KEY (target_id_topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5DEFEDE0 FOREIGN KEY (target_id_reply_id) REFERENCES reply (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A2F485210 FOREIGN KEY (target_id_wall_id) REFERENCES user_wall (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');
    }
}
