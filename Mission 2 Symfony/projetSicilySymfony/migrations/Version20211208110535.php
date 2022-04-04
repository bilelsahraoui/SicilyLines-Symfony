<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208110535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tarifer (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, periode_id INT DEFAULT NULL, liaison_id INT DEFAULT NULL, tarif DOUBLE PRECISION NOT NULL, INDEX IDX_6904C4FFC54C8C93 (type_id), INDEX IDX_6904C4FFF384C1CF (periode_id), INDEX IDX_6904C4FFED31185 (liaison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tarifer ADD CONSTRAINT FK_6904C4FFC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE tarifer ADD CONSTRAINT FK_6904C4FFF384C1CF FOREIGN KEY (periode_id) REFERENCES periode (id)');
        $this->addSql('ALTER TABLE tarifer ADD CONSTRAINT FK_6904C4FFED31185 FOREIGN KEY (liaison_id) REFERENCES liaison (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tarifer');
    }
}
