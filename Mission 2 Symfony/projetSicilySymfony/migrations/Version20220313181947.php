<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313181947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison DROP FOREIGN KEY FK_225AC37A8471592');
        $this->addSql('ALTER TABLE liaison DROP FOREIGN KEY FK_225AC376C79AC9B');
        $this->addSql('DROP INDEX IDX_225AC376C79AC9B ON liaison');
        $this->addSql('DROP INDEX IDX_225AC37A8471592 ON liaison');
        $this->addSql('ALTER TABLE liaison DROP port_depart_id_id, DROP port_arrivee_id_id');
        $this->addSql('ALTER TABLE port CHANGE name nom VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison ADD port_depart_id_id INT DEFAULT NULL, ADD port_arrivee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liaison ADD CONSTRAINT FK_225AC37A8471592 FOREIGN KEY (port_arrivee_id_id) REFERENCES port (id)');
        $this->addSql('ALTER TABLE liaison ADD CONSTRAINT FK_225AC376C79AC9B FOREIGN KEY (port_depart_id_id) REFERENCES port (id)');
        $this->addSql('CREATE INDEX IDX_225AC376C79AC9B ON liaison (port_depart_id_id)');
        $this->addSql('CREATE INDEX IDX_225AC37A8471592 ON liaison (port_arrivee_id_id)');
        $this->addSql('ALTER TABLE port CHANGE nom name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
