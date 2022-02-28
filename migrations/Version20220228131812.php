<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228131812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Finalisation des entités relationnelles et des amorces';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hook (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sub_hook LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A458435561220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hook ADD CONSTRAINT FK_A458435561220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE encounter ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE encounter ADD CONSTRAINT FK_69D229CA61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_69D229CA61220EA6 ON encounter (creator_id)');
        $this->addSql('ALTER TABLE intrigue ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE intrigue ADD CONSTRAINT FK_688D27161220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_688D27161220EA6 ON intrigue (creator_id)');
        $this->addSql('ALTER TABLE item ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E61220EA6 ON item (creator_id)');
        $this->addSql('ALTER TABLE location ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB61220EA6 ON location (creator_id)');
        $this->addSql('ALTER TABLE npc ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE npc ADD CONSTRAINT FK_468C762C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_468C762C61220EA6 ON npc (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encounter DROP FOREIGN KEY FK_69D229CA61220EA6');
        $this->addSql('ALTER TABLE hook DROP FOREIGN KEY FK_A458435561220EA6');
        $this->addSql('ALTER TABLE intrigue DROP FOREIGN KEY FK_688D27161220EA6');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E61220EA6');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB61220EA6');
        $this->addSql('ALTER TABLE npc DROP FOREIGN KEY FK_468C762C61220EA6');
        $this->addSql('DROP TABLE hook');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_69D229CA61220EA6 ON encounter');
        $this->addSql('ALTER TABLE encounter DROP creator_id, DROP created_at, DROP updated_at');
        $this->addSql('DROP INDEX IDX_688D27161220EA6 ON intrigue');
        $this->addSql('ALTER TABLE intrigue DROP creator_id, DROP created_at, DROP updated_at');
        $this->addSql('DROP INDEX IDX_1F1B251E61220EA6 ON item');
        $this->addSql('ALTER TABLE item DROP creator_id, DROP created_at, DROP updated_at');
        $this->addSql('DROP INDEX IDX_5E9E89CB61220EA6 ON location');
        $this->addSql('ALTER TABLE location DROP creator_id, DROP created_at, DROP updated_at');
        $this->addSql('DROP INDEX IDX_468C762C61220EA6 ON npc');
        $this->addSql('ALTER TABLE npc DROP creator_id, DROP created_at, DROP updated_at');
    }
}
