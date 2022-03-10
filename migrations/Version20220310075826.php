<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310075826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du ManyToMany récursif';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE encounter_encounter (encounter_source INT NOT NULL, encounter_target INT NOT NULL, INDEX IDX_3F975A105B6C987B (encounter_source), INDEX IDX_3F975A104289C8F4 (encounter_target), PRIMARY KEY(encounter_source, encounter_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intrigue_intrigue (intrigue_source INT NOT NULL, intrigue_target INT NOT NULL, INDEX IDX_E608766E17AD5AC4 (intrigue_source), INDEX IDX_E608766EE480A4B (intrigue_target), PRIMARY KEY(intrigue_source, intrigue_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_item (item_source INT NOT NULL, item_target INT NOT NULL, INDEX IDX_D72B61E5D9730ABC (item_source), INDEX IDX_D72B61E5C0965A33 (item_target), PRIMARY KEY(item_source, item_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_location (location_source INT NOT NULL, location_target INT NOT NULL, INDEX IDX_8FCBAB2914F8F771 (location_source), INDEX IDX_8FCBAB29D1DA7FE (location_target), PRIMARY KEY(location_source, location_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE npc_npc (npc_source INT NOT NULL, npc_target INT NOT NULL, INDEX IDX_B9F65850B9B67FB6 (npc_source), INDEX IDX_B9F65850A0532F39 (npc_target), PRIMARY KEY(npc_source, npc_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE encounter_encounter ADD CONSTRAINT FK_3F975A105B6C987B FOREIGN KEY (encounter_source) REFERENCES encounter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE encounter_encounter ADD CONSTRAINT FK_3F975A104289C8F4 FOREIGN KEY (encounter_target) REFERENCES encounter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intrigue_intrigue ADD CONSTRAINT FK_E608766E17AD5AC4 FOREIGN KEY (intrigue_source) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intrigue_intrigue ADD CONSTRAINT FK_E608766EE480A4B FOREIGN KEY (intrigue_target) REFERENCES intrigue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_item ADD CONSTRAINT FK_D72B61E5D9730ABC FOREIGN KEY (item_source) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_item ADD CONSTRAINT FK_D72B61E5C0965A33 FOREIGN KEY (item_target) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_location ADD CONSTRAINT FK_8FCBAB2914F8F771 FOREIGN KEY (location_source) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_location ADD CONSTRAINT FK_8FCBAB29D1DA7FE FOREIGN KEY (location_target) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_npc ADD CONSTRAINT FK_B9F65850B9B67FB6 FOREIGN KEY (npc_source) REFERENCES npc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE npc_npc ADD CONSTRAINT FK_B9F65850A0532F39 FOREIGN KEY (npc_target) REFERENCES npc (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE encounter_encounter');
        $this->addSql('DROP TABLE intrigue_intrigue');
        $this->addSql('DROP TABLE item_item');
        $this->addSql('DROP TABLE location_location');
        $this->addSql('DROP TABLE npc_npc');
    }
}
