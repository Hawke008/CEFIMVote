<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722073802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE electeurs (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, signature LONGBLOB NOT NULL, INDEX IDX_A6C2AB70613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsables (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, signature VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_853808A5E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, responsable_id INT DEFAULT NULL, heure_debut DATETIME DEFAULT NULL, heure_fin DATETIME DEFAULT NULL, promotion VARCHAR(255) DEFAULT NULL, date_debut_promo DATETIME NOT NULL, date_fin_promo DATETIME NOT NULL, ville VARCHAR(255) DEFAULT NULL, INDEX IDX_9A609D1353C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE electeurs ADD CONSTRAINT FK_A6C2AB70613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D1353C59D72 FOREIGN KEY (responsable_id) REFERENCES responsables (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D1353C59D72');
        $this->addSql('ALTER TABLE electeurs DROP FOREIGN KEY FK_A6C2AB70613FECDF');
        $this->addSql('DROP TABLE electeurs');
        $this->addSql('DROP TABLE responsables');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
