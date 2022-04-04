<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208120243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE port ADD port_depart_id INT DEFAULT NULL, ADD port_arrivee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE port ADD CONSTRAINT FK_43915DCC94C9CCD3 FOREIGN KEY (port_depart_id) REFERENCES liaison (id)');
        $this->addSql('ALTER TABLE port ADD CONSTRAINT FK_43915DCC141EAE06 FOREIGN KEY (port_arrivee_id) REFERENCES liaison (id)');
        $this->addSql('CREATE INDEX IDX_43915DCC94C9CCD3 ON port (port_depart_id)');
        $this->addSql('CREATE INDEX IDX_43915DCC141EAE06 ON port (port_arrivee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE port DROP FOREIGN KEY FK_43915DCC94C9CCD3');
        $this->addSql('ALTER TABLE port DROP FOREIGN KEY FK_43915DCC141EAE06');
        $this->addSql('DROP INDEX IDX_43915DCC94C9CCD3 ON port');
        $this->addSql('DROP INDEX IDX_43915DCC141EAE06 ON port');
        $this->addSql('ALTER TABLE port DROP port_depart_id, DROP port_arrivee_id');
    }
}
