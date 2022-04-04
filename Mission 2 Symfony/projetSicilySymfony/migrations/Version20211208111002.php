<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208111002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, bateau_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, nb_max INT NOT NULL, INDEX IDX_3C914DFDA9706509 (bateau_id), INDEX IDX_3C914DFDBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDA9706509 FOREIGN KEY (bateau_id) REFERENCES bateau (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('DROP INDEX UK_Reservation_Type ON participer');
        $this->addSql('DROP INDEX UK_Equipement_Bateau ON proposer');
        $this->addSql('DROP INDEX UK_Type_Periode_Liaison ON tarifer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contenir');
        $this->addSql('CREATE UNIQUE INDEX UK_Reservation_Type ON participer (reservation_id, type_id)');
        $this->addSql('CREATE UNIQUE INDEX UK_Equipement_Bateau ON proposer (equipement_id, bateau_id)');
        $this->addSql('CREATE UNIQUE INDEX UK_Type_Periode_Liaison ON tarifer (type_id, periode_id, liaison_id)');
    }
}
