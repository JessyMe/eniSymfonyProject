<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215160521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lieu_ville');
        $this->addSql('DROP TABLE sortie_lieu');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D615DFCFB2');
        $this->addSql('DROP INDEX IDX_5E90F6D615DFCFB2 ON inscription');
        $this->addSql('ALTER TABLE inscription DROP sorties_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lieu_ville (lieu_id INT NOT NULL, ville_id INT NOT NULL, INDEX IDX_86F37ED86AB213CC (lieu_id), INDEX IDX_86F37ED8A73F0036 (ville_id), PRIMARY KEY(lieu_id, ville_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sortie_lieu (sortie_id INT NOT NULL, lieu_id INT NOT NULL, INDEX IDX_28A3C35ACC72D953 (sortie_id), INDEX IDX_28A3C35A6AB213CC (lieu_id), PRIMARY KEY(sortie_id, lieu_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lieu_ville ADD CONSTRAINT FK_86F37ED86AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu_ville ADD CONSTRAINT FK_86F37ED8A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sortie_lieu ADD CONSTRAINT FK_28A3C35A6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sortie_lieu ADD CONSTRAINT FK_28A3C35ACC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription ADD sorties_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D615DFCFB2 FOREIGN KEY (sorties_id) REFERENCES sortie (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D615DFCFB2 ON inscription (sorties_id)');
    }
}
