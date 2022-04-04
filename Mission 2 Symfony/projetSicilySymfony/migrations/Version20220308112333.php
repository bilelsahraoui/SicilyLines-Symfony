<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308112333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE port (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liaison ADD port_depart_id_id INT DEFAULT NULL, ADD port_arrivee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liaison ADD CONSTRAINT FK_225AC376C79AC9B FOREIGN KEY (port_depart_id_id) REFERENCES port (id)');
        $this->addSql('ALTER TABLE liaison ADD CONSTRAINT FK_225AC37A8471592 FOREIGN KEY (port_arrivee_id_id) REFERENCES port (id)');
        $this->addSql('CREATE INDEX IDX_225AC376C79AC9B ON liaison (port_depart_id_id)');
        $this->addSql('CREATE INDEX IDX_225AC37A8471592 ON liaison (port_arrivee_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison DROP FOREIGN KEY FK_225AC376C79AC9B');
        $this->addSql('ALTER TABLE liaison DROP FOREIGN KEY FK_225AC37A8471592');
        $this->addSql('DROP TABLE port');
        $this->addSql('DROP INDEX IDX_225AC376C79AC9B ON liaison');
        $this->addSql('DROP INDEX IDX_225AC37A8471592 ON liaison');
        $this->addSql('ALTER TABLE liaison DROP port_depart_id_id, DROP port_arrivee_id_id');
    }
}
