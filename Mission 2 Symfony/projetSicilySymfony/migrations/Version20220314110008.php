<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314110008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, nb_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenir_bateau (contenir_id INT NOT NULL, bateau_id INT NOT NULL, INDEX IDX_BFA35CCB1982B715 (contenir_id), INDEX IDX_BFA35CCBA9706509 (bateau_id), PRIMARY KEY(contenir_id, bateau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenir_categorie (contenir_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_4267EF191982B715 (contenir_id), INDEX IDX_4267EF19BCF5E72D (categorie_id), PRIMARY KEY(contenir_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenir_bateau ADD CONSTRAINT FK_BFA35CCB1982B715 FOREIGN KEY (contenir_id) REFERENCES contenir (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenir_bateau ADD CONSTRAINT FK_BFA35CCBA9706509 FOREIGN KEY (bateau_id) REFERENCES bateau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenir_categorie ADD CONSTRAINT FK_4267EF191982B715 FOREIGN KEY (contenir_id) REFERENCES contenir (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenir_categorie ADD CONSTRAINT FK_4267EF19BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenir_bateau DROP FOREIGN KEY FK_BFA35CCB1982B715');
        $this->addSql('ALTER TABLE contenir_categorie DROP FOREIGN KEY FK_4267EF191982B715');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP TABLE contenir_bateau');
        $this->addSql('DROP TABLE contenir_categorie');
    }
}
