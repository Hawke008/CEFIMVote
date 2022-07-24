<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722074944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidats (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, titulaire_id INT DEFAULT NULL, suppleant_id INT DEFAULT NULL, INDEX IDX_3C663B15613FECDF (session_id), UNIQUE INDEX UNIQ_3C663B15A10273AA (titulaire_id), UNIQUE INDEX UNIQ_3C663B1567A3C51B (suppleant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B15613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B15A10273AA FOREIGN KEY (titulaire_id) REFERENCES electeurs (id)');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B1567A3C51B FOREIGN KEY (suppleant_id) REFERENCES electeurs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE candidats');
    }
}
