<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200803131329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS cat_forum (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS forum (id INT AUTO_INCREMENT NOT NULL, cat_forum_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, position INT NOT NULL, is_locked TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_852BBECDABE4FF93 (cat_forum_id), INDEX IDX_852BBECD727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS like_wall (id INT AUTO_INCREMENT NOT NULL, post_wall_id INT NOT NULL, author_id INT NOT NULL, is_like TINYINT(1) DEFAULT NULL, is_dislike TINYINT(1) DEFAULT NULL, INDEX IDX_CA803388FED194C2 (post_wall_id), INDEX IDX_CA803388F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS navbar_element (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, value VARCHAR(191) NOT NULL, postion INT UNSIGNED DEFAULT 0 NOT NULL, type ENUM(\'link\', \'dropdown\'), parent_id INT UNSIGNED DEFAULT NULL, new_tab TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS topic (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, author_id INT NOT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, posted_at DATETIME NOT NULL, is_locked TINYINT(1) NOT NULL, is_pined TINYINT(1) NOT NULL, last_reply_at DATETIME NOT NULL, INDEX IDX_9D40DE1B29CCBAD0 (forum_id), INDEX IDX_9D40DE1BF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS topic_tag (topic_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_302AC6211F55203D (topic_id), INDEX IDX_302AC621BAD26311 (tag_id), PRIMARY KEY(topic_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_login DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS user_user_rank (user_id INT NOT NULL, user_rank_id INT NOT NULL, INDEX IDX_F21829C8A76ED395 (user_id), INDEX IDX_F21829C82E1B7985 (user_rank_id), PRIMARY KEY(user_id, user_rank_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS user_info (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, gender VARCHAR(255) DEFAULT NULL, is_gender TINYINT(1) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, is_birth_date TINYINT(1) DEFAULT NULL, home_page VARCHAR(255) DEFAULT NULL, is_home_page TINYINT(1) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, is_country TINYINT(1) DEFAULT NULL, job VARCHAR(255) DEFAULT NULL, is_job TINYINT(1) DEFAULT NULL, steam VARCHAR(255) DEFAULT NULL, is_steam TINYINT(1) DEFAULT NULL, minecraft VARCHAR(255) DEFAULT NULL, is_minecraft TINYINT(1) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, is_twitch TINYINT(1) DEFAULT NULL, discord VARCHAR(255) DEFAULT NULL, is_discord TINYINT(1) DEFAULT NULL, is_public TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B1087D9EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS user_rank (id INT AUTO_INCREMENT NOT NULL, can_manage_forum TINYINT(1) NOT NULL, has_repport_panel TINYINT(1) NOT NULL, can_manage_wall TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, priority INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE  IF NOT EXISTS user_wall (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, posted_at DATETIME NOT NULL, INDEX IDX_6974A33FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDABE4FF93 FOREIGN KEY (cat_forum_id) REFERENCES cat_forum (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD727ACA70 FOREIGN KEY (parent_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE like_wall ADD CONSTRAINT FK_CA803388FED194C2 FOREIGN KEY (post_wall_id) REFERENCES user_wall (id)');
        $this->addSql('ALTER TABLE like_wall ADD CONSTRAINT FK_CA803388F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic_tag ADD CONSTRAINT FK_302AC6211F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_tag ADD CONSTRAINT FK_302AC621BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_rank ADD CONSTRAINT FK_F21829C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_rank ADD CONSTRAINT FK_F21829C82E1B7985 FOREIGN KEY (user_rank_id) REFERENCES user_rank (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_wall ADD CONSTRAINT FK_6974A33FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDABE4FF93');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD727ACA70');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B29CCBAD0');
        $this->addSql('ALTER TABLE topic_tag DROP FOREIGN KEY FK_302AC621BAD26311');
        $this->addSql('ALTER TABLE topic_tag DROP FOREIGN KEY FK_302AC6211F55203D');
        $this->addSql('ALTER TABLE like_wall DROP FOREIGN KEY FK_CA803388F675F31B');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BF675F31B');
        $this->addSql('ALTER TABLE user_user_rank DROP FOREIGN KEY FK_F21829C8A76ED395');
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9EA76ED395');
        $this->addSql('ALTER TABLE user_wall DROP FOREIGN KEY FK_6974A33FA76ED395');
        $this->addSql('ALTER TABLE user_user_rank DROP FOREIGN KEY FK_F21829C82E1B7985');
        $this->addSql('ALTER TABLE like_wall DROP FOREIGN KEY FK_CA803388FED194C2');
        $this->addSql('DROP TABLE cat_forum');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE like_wall');
        $this->addSql('DROP TABLE navbar_element');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE topic_tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user_rank');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE user_rank');
        $this->addSql('DROP TABLE user_wall');
    }
}
