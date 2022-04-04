<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314111230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bateau DROP FOREIGN KEY FK_A664B05A1982B715');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD6341982B715');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP INDEX IDX_A664B05A1982B715 ON bateau');
        $this->addSql('ALTER TABLE bateau DROP contenir_id');
        $this->addSql('DROP INDEX IDX_497DD6341982B715 ON categorie');
        $this->addSql('ALTER TABLE categorie DROP contenir_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, nb_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bateau ADD contenir_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bateau ADD CONSTRAINT FK_A664B05A1982B715 FOREIGN KEY (contenir_id) REFERENCES contenir (id)');
        $this->addSql('CREATE INDEX IDX_A664B05A1982B715 ON bateau (contenir_id)');
        $this->addSql('ALTER TABLE categorie ADD contenir_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD6341982B715 FOREIGN KEY (contenir_id) REFERENCES contenir (id)');
        $this->addSql('CREATE INDEX IDX_497DD6341982B715 ON categorie (contenir_id)');
    }
}
