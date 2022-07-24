<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722080647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, electeur_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, tour SMALLINT DEFAULT NULL, INDEX IDX_518B7ACFE0557D80 (electeur_id), INDEX IDX_518B7ACF8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFE0557D80 FOREIGN KEY (electeur_id) REFERENCES electeurs (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidats (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE votes');
    }
}
