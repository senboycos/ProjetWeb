<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429041307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, date_anniv DATE DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON user (login)');
        $this->addSql('DROP INDEX IDX_1DB893A52FBB81F');
        $this->addSql('DROP INDEX IDX_1DB893A5F7384557');
        $this->addSql('DROP INDEX ahe_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asso_panier_produit AS SELECT id, id_panier, id_produit, quantite FROM asso_panier_produit');
        $this->addSql('DROP TABLE asso_panier_produit');
        $this->addSql('CREATE TABLE asso_panier_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_panier INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_1DB893A52FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1DB893A5F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asso_panier_produit (id, id_panier, id_produit, quantite) SELECT id, id_panier, id_produit, quantite FROM __temp__asso_panier_produit');
        $this->addSql('DROP TABLE __temp__asso_panier_produit');
        $this->addSql('CREATE INDEX IDX_1DB893A52FBB81F ON asso_panier_produit (id_panier)');
        $this->addSql('CREATE INDEX IDX_1DB893A5F7384557 ON asso_panier_produit (id_produit)');
        $this->addSql('CREATE UNIQUE INDEX ahe_idx ON asso_panier_produit (id_panier, id_produit)');
        $this->addSql('DROP INDEX UNIQ_24CC0DF219EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id, client_id, quantite FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, quantite INTEGER DEFAULT NULL, CONSTRAINT FK_24CC0DF219EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier (id, client_id, quantite) SELECT id, client_id, quantite FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24CC0DF219EB6921 ON panier (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_1DB893A52FBB81F');
        $this->addSql('DROP INDEX IDX_1DB893A5F7384557');
        $this->addSql('DROP INDEX ahe_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asso_panier_produit AS SELECT id, id_panier, id_produit, quantite FROM asso_panier_produit');
        $this->addSql('DROP TABLE asso_panier_produit');
        $this->addSql('CREATE TABLE asso_panier_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_panier INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO asso_panier_produit (id, id_panier, id_produit, quantite) SELECT id, id_panier, id_produit, quantite FROM __temp__asso_panier_produit');
        $this->addSql('DROP TABLE __temp__asso_panier_produit');
        $this->addSql('CREATE INDEX IDX_1DB893A52FBB81F ON asso_panier_produit (id_panier)');
        $this->addSql('CREATE INDEX IDX_1DB893A5F7384557 ON asso_panier_produit (id_produit)');
        $this->addSql('CREATE UNIQUE INDEX ahe_idx ON asso_panier_produit (id_panier, id_produit)');
        $this->addSql('DROP INDEX UNIQ_24CC0DF219EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id, client_id, quantite FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO panier (id, client_id, quantite) SELECT id, client_id, quantite FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24CC0DF219EB6921 ON panier (client_id)');
    }
}
