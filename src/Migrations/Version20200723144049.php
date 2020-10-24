<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723144049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E7F2DEE08 ON paiement (facture_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27139DF194, ADD UNIQUE INDEX UNIQ_29A5EC27139DF194 (promotion_id)');
        $this->addSql('ALTER TABLE ville ADD lat DOUBLE PRECISION NOT NULL, ADD lng DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E7F2DEE08');
        $this->addSql('DROP INDEX UNIQ_B1DC7A1E7F2DEE08 ON paiement');
        $this->addSql('ALTER TABLE paiement DROP facture_id');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27139DF194, ADD INDEX IDX_29A5EC27139DF194 (promotion_id)');
        $this->addSql('ALTER TABLE ville DROP lat, DROP lng');
    }
}
