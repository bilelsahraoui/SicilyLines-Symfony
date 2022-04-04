<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308133809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bateau DROP FOREIGN KEY FK_A664B05AED2BB15B');
        $this->addSql('DROP INDEX IDX_A664B05AED2BB15B ON bateau');
        $this->addSql('ALTER TABLE bateau DROP traversee_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bateau ADD traversee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bateau ADD CONSTRAINT FK_A664B05AED2BB15B FOREIGN KEY (traversee_id) REFERENCES traversee (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A664B05AED2BB15B ON bateau (traversee_id)');
    }
}
