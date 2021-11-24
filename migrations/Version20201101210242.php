<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101210242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE navbar_sub_menu (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, nom VARCHAR(255) NOT NULL, position INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_46888921727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE navbar_sub_menu ADD CONSTRAINT FK_46888921727ACA70 FOREIGN KEY (parent_id) REFERENCES navbar_menu (id)');
        $this->addSql('ALTER TABLE navbar_element CHANGE type type ENUM(\'link\', \'dropdown\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE navbar_sub_menu');
        $this->addSql('ALTER TABLE navbar_element CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
