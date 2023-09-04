<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511193259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roster ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE roster ADD CONSTRAINT FK_60B9ADF9E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_60B9ADF9E48FD905 ON roster (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roster DROP FOREIGN KEY FK_60B9ADF9E48FD905');
        $this->addSql('DROP INDEX IDX_60B9ADF9E48FD905 ON roster');
        $this->addSql('ALTER TABLE roster DROP game_id');
    }
}
