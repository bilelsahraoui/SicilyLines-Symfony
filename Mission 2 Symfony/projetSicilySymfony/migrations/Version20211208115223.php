<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208115223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bateau ADD traversee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bateau ADD CONSTRAINT FK_A664B05AED2BB15B FOREIGN KEY (traversee_id) REFERENCES traversee (id)');
        $this->addSql('CREATE INDEX IDX_A664B05AED2BB15B ON bateau (traversee_id)');
        $this->addSql('ALTER TABLE categorie ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_497DD634C54C8C93 ON categorie (type_id)');
        $this->addSql('ALTER TABLE client ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_C7440455B83297E7 ON client (reservation_id)');
        $this->addSql('ALTER TABLE liaison ADD traversee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liaison ADD CONSTRAINT FK_225AC37ED2BB15B FOREIGN KEY (traversee_id) REFERENCES traversee (id)');
        $this->addSql('CREATE INDEX IDX_225AC37ED2BB15B ON liaison (traversee_id)');
        $this->addSql('ALTER TABLE secteur ADD liaison_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE secteur ADD CONSTRAINT FK_8045251FED31185 FOREIGN KEY (liaison_id) REFERENCES liaison (id)');
        $this->addSql('CREATE INDEX IDX_8045251FED31185 ON secteur (liaison_id)');
        $this->addSql('ALTER TABLE traversee ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE traversee ADD CONSTRAINT FK_B688F501B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_B688F501B83297E7 ON traversee (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bateau DROP FOREIGN KEY FK_A664B05AED2BB15B');
        $this->addSql('DROP INDEX IDX_A664B05AED2BB15B ON bateau');
        $this->addSql('ALTER TABLE bateau DROP traversee_id');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634C54C8C93');
        $this->addSql('DROP INDEX IDX_497DD634C54C8C93 ON categorie');
        $this->addSql('ALTER TABLE categorie DROP type_id');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455B83297E7');
        $this->addSql('DROP INDEX IDX_C7440455B83297E7 ON client');
        $this->addSql('ALTER TABLE client DROP reservation_id');
        $this->addSql('ALTER TABLE liaison DROP FOREIGN KEY FK_225AC37ED2BB15B');
        $this->addSql('DROP INDEX IDX_225AC37ED2BB15B ON liaison');
        $this->addSql('ALTER TABLE liaison DROP traversee_id');
        $this->addSql('ALTER TABLE secteur DROP FOREIGN KEY FK_8045251FED31185');
        $this->addSql('DROP INDEX IDX_8045251FED31185 ON secteur');
        $this->addSql('ALTER TABLE secteur DROP liaison_id');
        $this->addSql('ALTER TABLE traversee DROP FOREIGN KEY FK_B688F501B83297E7');
        $this->addSql('DROP INDEX IDX_B688F501B83297E7 ON traversee');
        $this->addSql('ALTER TABLE traversee DROP reservation_id');
    }
}
