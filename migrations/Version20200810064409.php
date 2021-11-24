<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810064409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_trophy (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, trophy_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_7478E1D4A76ED395 (user_id), INDEX IDX_7478E1D4F59AEEEF (trophy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_trophy ADD CONSTRAINT FK_7478E1D4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_trophy ADD CONSTRAINT FK_7478E1D4F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophy (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_trophy');
    }
}
