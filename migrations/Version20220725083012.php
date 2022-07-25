<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725083012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE binomes (id INT AUTO_INCREMENT NOT NULL, electeur_titulaire VARCHAR(255) DEFAULT NULL, electeur_suppleant VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidats (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, titulaire_id INT DEFAULT NULL, suppleant_id INT DEFAULT NULL, INDEX IDX_3C663B15613FECDF (session_id), UNIQUE INDEX UNIQ_3C663B15A10273AA (titulaire_id), UNIQUE INDEX UNIQ_3C663B1567A3C51B (suppleant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE electeurs (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, signature LONGBLOB NOT NULL, INDEX IDX_A6C2AB70613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsables (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, signature VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_853808A5E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, responsable_id INT DEFAULT NULL, heure_debut DATETIME DEFAULT NULL, heure_fin DATETIME DEFAULT NULL, promotion VARCHAR(255) DEFAULT NULL, date_debut_promo DATETIME NOT NULL, date_fin_promo DATETIME NOT NULL, ville VARCHAR(255) DEFAULT NULL, INDEX IDX_9A609D1353C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, electeur_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, tour SMALLINT DEFAULT NULL, INDEX IDX_518B7ACFE0557D80 (electeur_id), INDEX IDX_518B7ACF8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B15613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B15A10273AA FOREIGN KEY (titulaire_id) REFERENCES electeurs (id)');
        $this->addSql('ALTER TABLE candidats ADD CONSTRAINT FK_3C663B1567A3C51B FOREIGN KEY (suppleant_id) REFERENCES electeurs (id)');
        $this->addSql('ALTER TABLE electeurs ADD CONSTRAINT FK_A6C2AB70613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D1353C59D72 FOREIGN KEY (responsable_id) REFERENCES responsables (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFE0557D80 FOREIGN KEY (electeur_id) REFERENCES electeurs (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidats (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF8D0EB82');
        $this->addSql('ALTER TABLE candidats DROP FOREIGN KEY FK_3C663B15A10273AA');
        $this->addSql('ALTER TABLE candidats DROP FOREIGN KEY FK_3C663B1567A3C51B');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFE0557D80');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D1353C59D72');
        $this->addSql('ALTER TABLE candidats DROP FOREIGN KEY FK_3C663B15613FECDF');
        $this->addSql('ALTER TABLE electeurs DROP FOREIGN KEY FK_A6C2AB70613FECDF');
        $this->addSql('DROP TABLE binomes');
        $this->addSql('DROP TABLE candidats');
        $this->addSql('DROP TABLE electeurs');
        $this->addSql('DROP TABLE responsables');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE votes');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
