<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208103625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proposer (id INT AUTO_INCREMENT NOT NULL, equipement_id INT DEFAULT NULL, bateau_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_21866C15806F0F5C (equipement_id), INDEX IDX_21866C15A9706509 (bateau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE proposer ADD CONSTRAINT FK_21866C15A9706509 FOREIGN KEY (bateau_id) REFERENCES bateau (id)');
        $this->addSql('DROP INDEX UK_Reservation_Type ON participer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE proposer');
        $this->addSql('CREATE UNIQUE INDEX UK_Reservation_Type ON participer (reservation_id, type_id)');
    }
}
