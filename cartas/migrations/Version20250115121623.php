<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115121623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game CHANGE user0_id user0_id INT DEFAULT NULL, CHANGE user1_id user1_id INT DEFAULT NULL, CHANGE card0_id card0_id INT DEFAULT NULL, CHANGE card1_id card1_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game CHANGE user0_id user0_id INT NOT NULL, CHANGE user1_id user1_id INT NOT NULL, CHANGE card0_id card0_id INT NOT NULL, CHANGE card1_id card1_id INT NOT NULL');
    }
}
