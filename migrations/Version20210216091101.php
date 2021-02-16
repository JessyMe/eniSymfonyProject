<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216091101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F22CB716C7');
        $this->addSql('DROP INDEX IDX_3C3FD3F22CB716C7 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE yes_id campus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2AF5D55E1 ON sortie (campus_id)');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE role role VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2AF5D55E1');
        $this->addSql('DROP INDEX IDX_3C3FD3F2AF5D55E1 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE campus_id yes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F22CB716C7 FOREIGN KEY (yes_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F22CB716C7 ON sortie (yes_id)');
        $this->addSql('ALTER TABLE `user` CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
