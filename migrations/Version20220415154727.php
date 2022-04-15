<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415154727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asso_panier_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_panier INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1DB893A52FBB81F ON asso_panier_produit (id_panier)');
        $this->addSql('CREATE INDEX IDX_1DB893A5F7384557 ON asso_panier_produit (id_produit)');
        $this->addSql('CREATE UNIQUE INDEX ahe_idx ON asso_panier_produit (id_panier, id_produit)');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(60) DEFAULT NULL, prix INTEGER DEFAULT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(40) NOT NULL, motdepasse VARCHAR(40) NOT NULL, nom VARCHAR(40) NOT NULL, prenom VARCHAR(40) NOT NULL, date_naissance DATETIME NOT NULL, isadmin BOOLEAN NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE asso_panier_produit');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE utilisateur');
    }
}
