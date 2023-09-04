<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510202255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roster (id INT AUTO_INCREMENT NOT NULL, game VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roster_user (roster_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_85E1FB9E75404483 (roster_id), INDEX IDX_85E1FB9EA76ED395 (user_id), PRIMARY KEY(roster_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE roster_user ADD CONSTRAINT FK_85E1FB9E75404483 FOREIGN KEY (roster_id) REFERENCES roster (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roster_user ADD CONSTRAINT FK_85E1FB9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roster_user DROP FOREIGN KEY FK_85E1FB9E75404483');
        $this->addSql('ALTER TABLE roster_user DROP FOREIGN KEY FK_85E1FB9EA76ED395');
        $this->addSql('DROP TABLE roster');
        $this->addSql('DROP TABLE roster_user');
    }
}
