<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723123504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, tele VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agriculteur (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, cin VARCHAR(10) NOT NULL, adresse VARCHAR(255) NOT NULL, reference VARCHAR(15) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tele VARCHAR(255) NOT NULL, INDEX IDX_2366443B131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_bancaire (id INT AUTO_INCREMENT NOT NULL, type_carte_id INT DEFAULT NULL, client_id INT DEFAULT NULL, numro_carte VARCHAR(16) NOT NULL, date_expiration DATE NOT NULL, INDEX IDX_59E3C22D438E94A (type_carte_id), INDEX IDX_59E3C22D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, image VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(15) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, tele VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, reference VARCHAR(15) NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, client_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, INDEX IDX_67F068BCF347EFB (produit_id), INDEX IDX_67F068BC19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, libelle VARCHAR(40) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_E2E2D1EEA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, paiement_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, reference VARCHAR(15) NOT NULL, date_creation DATE NOT NULL, UNIQUE INDEX UNIQ_FE8664102A4C4478 (paiement_id), UNIQUE INDEX UNIQ_FE86641082EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, qte_commande DOUBLE PRECISION NOT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), INDEX IDX_3170B74BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, type_livraison_id INT DEFAULT NULL, date_livraison DATE NOT NULL, adresse VARCHAR(255) NOT NULL, livree TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A60C9F1F82EA2E54 (commande_id), INDEX IDX_A60C9F1F9174120 (type_livraison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE market (id INT AUTO_INCREMENT NOT NULL, agriculteur_id INT DEFAULT NULL, libelle VARCHAR(30) NOT NULL, image VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_6BAC85CB7EBB810E (agriculteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, carte_bancaire_id INT DEFAULT NULL, reference VARCHAR(15) NOT NULL, INDEX IDX_B1DC7A1E961B16D4 (carte_bancaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, market_id INT DEFAULT NULL, tva_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, stock DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, date_production DATE NOT NULL, image VARCHAR(100) NOT NULL, date_ajout DATE NOT NULL, is_bio TINYINT(1) NOT NULL, information LONGTEXT NOT NULL, libelle VARCHAR(30) NOT NULL, updated_at DATETIME DEFAULT NULL, unite VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27622F3F37 (market_id), INDEX IDX_29A5EC274D79775F (tva_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), INDEX IDX_29A5EC27139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, reduction DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation_produit (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, INDEX IDX_74C6E58B19EB6921 (client_id), INDEX IDX_74C6E58BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, pourcentage DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_carte (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_livraison (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, frais DOUBLE PRECISION NOT NULL, duree INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, agriculteur_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64919EB6921 (client_id), UNIQUE INDEX UNIQ_8D93D649642B8210 (admin_id), UNIQUE INDEX UNIQ_8D93D6497EBB810E (agriculteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, date_vente DATE NOT NULL, qte_vendu DOUBLE PRECISION NOT NULL, prix_vente DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION NOT NULL, INDEX IDX_888A2A4CF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_43C3D9C398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agriculteur ADD CONSTRAINT FK_2366443B131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE carte_bancaire ADD CONSTRAINT FK_59E3C22D438E94A FOREIGN KEY (type_carte_id) REFERENCES type_carte (id)');
        $this->addSql('ALTER TABLE carte_bancaire ADD CONSTRAINT FK_59E3C22D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EEA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664102A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F9174120 FOREIGN KEY (type_livraison_id) REFERENCES type_livraison (id)');
        $this->addSql('ALTER TABLE market ADD CONSTRAINT FK_6BAC85CB7EBB810E FOREIGN KEY (agriculteur_id) REFERENCES agriculteur (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E961B16D4 FOREIGN KEY (carte_bancaire_id) REFERENCES carte_bancaire (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27622F3F37 FOREIGN KEY (market_id) REFERENCES market (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC274D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE recommendation_produit ADD CONSTRAINT FK_74C6E58B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE recommendation_produit ADD CONSTRAINT FK_74C6E58BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497EBB810E FOREIGN KEY (agriculteur_id) REFERENCES agriculteur (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649642B8210');
        $this->addSql('ALTER TABLE market DROP FOREIGN KEY FK_6BAC85CB7EBB810E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497EBB810E');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E961B16D4');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE carte_bancaire DROP FOREIGN KEY FK_59E3C22D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC19EB6921');
        $this->addSql('ALTER TABLE recommendation_produit DROP FOREIGN KEY FK_74C6E58B19EB6921');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641082EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F82EA2E54');
        $this->addSql('ALTER TABLE agriculteur DROP FOREIGN KEY FK_2366443B131A4F72');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27622F3F37');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664102A4C4478');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF347EFB');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BF347EFB');
        $this->addSql('ALTER TABLE recommendation_produit DROP FOREIGN KEY FK_74C6E58BF347EFB');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CF347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27139DF194');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C398260155');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC274D79775F');
        $this->addSql('ALTER TABLE carte_bancaire DROP FOREIGN KEY FK_59E3C22D438E94A');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F9174120');
        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EEA73F0036');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE agriculteur');
        $this->addSql('DROP TABLE carte_bancaire');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE market');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE recommendation_produit');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP TABLE type_carte');
        $this->addSql('DROP TABLE type_livraison');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vente');
        $this->addSql('DROP TABLE ville');
    }
}
