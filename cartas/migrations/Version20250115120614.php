<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115120614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, user0_id INT NOT NULL, user1_id INT NOT NULL, card0_id INT NOT NULL, card1_id INT NOT NULL, finished TINYINT(1) NOT NULL, INDEX IDX_232B318CEE1243EE (user0_id), INDEX IDX_232B318C56AE248B (user1_id), INDEX IDX_232B318C55F4A6A4 (card0_id), INDEX IDX_232B318CED48C1C1 (card1_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CEE1243EE FOREIGN KEY (user0_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C56AE248B FOREIGN KEY (user1_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C55F4A6A4 FOREIGN KEY (card0_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CED48C1C1 FOREIGN KEY (card1_id) REFERENCES card (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CEE1243EE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C56AE248B');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C55F4A6A4');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CED48C1C1');
        $this->addSql('DROP TABLE game');
    }
}
