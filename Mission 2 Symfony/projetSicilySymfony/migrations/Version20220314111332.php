<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314111332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, bateau_id INT DEFAULT NULL, nb_max INT NOT NULL, INDEX IDX_3C914DFDBCF5E72D (categorie_id), INDEX IDX_3C914DFDA9706509 (bateau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDA9706509 FOREIGN KEY (bateau_id) REFERENCES bateau (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contenir');
    }
}
