<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208103909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UK_Reservation_Type ON participer');
        $this->addSql('DROP INDEX UK_Equipement_Bateau ON proposer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UK_Reservation_Type ON participer (reservation_id, type_id)');
        $this->addSql('CREATE UNIQUE INDEX UK_Equipement_Bateau ON proposer (equipement_id, bateau_id)');
    }
}
