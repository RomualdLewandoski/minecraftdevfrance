<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903073151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE navbar_element CHANGE type type ENUM(\'link\', \'dropdown\')');
        $this->addSql('ALTER TABLE report_reply ADD is_sanction TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE report_topic ADD is_sanction TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE report_user ADD is_sanction TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE report_wall ADD is_sanction TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE navbar_element CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE report_reply DROP is_sanction');
        $this->addSql('ALTER TABLE report_topic DROP is_sanction');
        $this->addSql('ALTER TABLE report_user DROP is_sanction');
        $this->addSql('ALTER TABLE report_wall DROP is_sanction');
    }
}
