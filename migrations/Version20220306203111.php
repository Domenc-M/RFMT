<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306203111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initialisation des relations many to many';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intrigue_encounter (intrigue_id INT NOT NULL, encounter_id INT NOT NULL, INDEX IDX_E43AA49B631F6BDE (intrigue_id), INDEX IDX_E43AA49BD6E2FADC (encounter_id), PRIMARY KEY(intrigue_id, encounter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_intrigue (item_id INT NOT NULL, intrigue_id INT NOT NULL, INDEX IDX_6A851ABA126F525E (item_id), INDEX IDX_6A851ABA631F6BDE (intrigue_id), PRIMARY KEY(item_id, intrigue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_encounter (item_id INT NOT NULL, encounter_id INT NOT NULL, INDEX IDX_65083F3A126F525E (item_id), INDEX IDX_65083F3AD6E2FADC (encounter_id), PRIMARY KEY(item_id, encounter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_item (location_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_9F1F905E64D218E (location_id), INDEX IDX_9F1F905E126F525E (item_id), PRIMARY KEY(location_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_intrigue (location_id INT NOT NULL, intrigue_id INT NOT NULL, INDEX IDX_D7DDF09364D218E (location_id), INDEX IDX_D7DDF093631F6BDE (intrigue_id), PRIMARY KEY(location_id, intrigue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_encounter (location_id INT NOT NULL, encounter_id INT NOT NULL, INDEX IDX_2707FFBC64D218E (location_id), INDEX IDX_2707FFBCD6E2FADC (encounter_id), PRIMARY KEY(location_id, encounter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE npc_location (npc_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_E8134CFCCA7D6B89 (npc_id), INDEX IDX_E8134CFC64D218E (location_id), PRIMARY KEY(npc_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE npc_item (npc_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_46576227CA7D6B89 (npc_id), INDEX IDX_46576227126F525E (item_id), PRIMARY KEY(npc_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE npc_intrigue (npc_id INT NOT NULL, intrigue_id INT NOT NULL, INDEX IDX_B0051746CA7D6B89 (npc_id), INDEX IDX_B0051746631F6BDE (intrigue_id), PRIMARY KEY(npc_id, intrigue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE npc_encounter (npc_id INT NOT NULL, encounter_id INT NOT NULL, INDEX IDX_D1D90100CA7D6B89 (npc_id), INDEX IDX_D1D90100D6E2FADC (encounter_id), PRIMARY KEY(npc_id, encounter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intrigue_encounter ADD CONSTRAINT FK_E43AA49B631F6BDE FOREIGN KEY (intrigue_id) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intrigue_encounter ADD CONSTRAINT FK_E43AA49BD6E2FADC FOREIGN KEY (encounter_id) REFERENCES encounter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_intrigue ADD CONSTRAINT FK_6A851ABA126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_intrigue ADD CONSTRAINT FK_6A851ABA631F6BDE FOREIGN KEY (intrigue_id) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_encounter ADD CONSTRAINT FK_65083F3A126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_encounter ADD CONSTRAINT FK_65083F3AD6E2FADC FOREIGN KEY (encounter_id) REFERENCES encounter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_item ADD CONSTRAINT FK_9F1F905E64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_item ADD CONSTRAINT FK_9F1F905E126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_intrigue ADD CONSTRAINT FK_D7DDF09364D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_intrigue ADD CONSTRAINT FK_D7DDF093631F6BDE FOREIGN KEY (intrigue_id) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_encounter ADD CONSTRAINT FK_2707FFBC64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_encounter ADD CONSTRAINT FK_2707FFBCD6E2FADC FOREIGN KEY (encounter_id) REFERENCES encounter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_location ADD CONSTRAINT FK_E8134CFCCA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_location ADD CONSTRAINT FK_E8134CFC64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_item ADD CONSTRAINT FK_46576227CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_item ADD CONSTRAINT FK_46576227126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_intrigue ADD CONSTRAINT FK_B0051746CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_intrigue ADD CONSTRAINT FK_B0051746631F6BDE FOREIGN KEY (intrigue_id) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_encounter ADD CONSTRAINT FK_D1D90100CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_encounter ADD CONSTRAINT FK_D1D90100D6E2FADC FOREIGN KEY (encounter_id) REFERENCES encounter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE intrigue_encounter');
        $this->addSql('DROP TABLE item_intrigue');
        $this->addSql('DROP TABLE item_encounter');
        $this->addSql('DROP TABLE location_item');
        $this->addSql('DROP TABLE location_intrigue');
        $this->addSql('DROP TABLE location_encounter');
        $this->addSql('DROP TABLE npc_location');
        $this->addSql('DROP TABLE npc_item');
        $this->addSql('DROP TABLE npc_intrigue');
        $this->addSql('DROP TABLE npc_encounter');
    }
}
