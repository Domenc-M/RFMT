<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228144746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du champ image';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encounter ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hook ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE intrigue ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE item ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE location ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE npc ADD img VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encounter DROP img');
        $this->addSql('ALTER TABLE hook DROP img');
        $this->addSql('ALTER TABLE intrigue DROP img');
        $this->addSql('ALTER TABLE item DROP img');
        $this->addSql('ALTER TABLE location DROP img');
        $this->addSql('ALTER TABLE npc DROP img');
    }
}
