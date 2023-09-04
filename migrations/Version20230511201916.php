<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511201916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cashprize INT NOT NULL, max_users SMALLINT NOT NULL, date DATETIME NOT NULL, INDEX IDX_BD5FB8D9E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_roster (tournament_id INT NOT NULL, roster_id INT NOT NULL, INDEX IDX_41353A933D1A3E7 (tournament_id), INDEX IDX_41353A975404483 (roster_id), PRIMARY KEY(tournament_id, roster_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE tournament_roster ADD CONSTRAINT FK_41353A933D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_roster ADD CONSTRAINT FK_41353A975404483 FOREIGN KEY (roster_id) REFERENCES roster (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roster CHANGE team_id team_id INT NOT NULL, CHANGE game_id game_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9E48FD905');
        $this->addSql('ALTER TABLE tournament_roster DROP FOREIGN KEY FK_41353A933D1A3E7');
        $this->addSql('ALTER TABLE tournament_roster DROP FOREIGN KEY FK_41353A975404483');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_roster');
        $this->addSql('ALTER TABLE roster CHANGE team_id team_id INT DEFAULT NULL, CHANGE game_id game_id INT DEFAULT NULL');
    }
}
