<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726180754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, published TINYINT(1) NOT NULL, created_at DATE NOT NULL, INDEX IDX_C0155143BCF5E72D (categorie_id), INDEX IDX_C0155143642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_blog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, content LONGTEXT NOT NULL, INDEX IDX_29ED9511A76ED395 (user_id), INDEX IDX_29ED9511DAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_blog (tag_id INT NOT NULL, blog_id INT NOT NULL, INDEX IDX_2E1AEEF5BAD26311 (tag_id), INDEX IDX_2E1AEEF5DAE07E97 (blog_id), PRIMARY KEY(tag_id, blog_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE commentaire_blog ADD CONSTRAINT FK_29ED9511A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_blog ADD CONSTRAINT FK_29ED9511DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE tag_blog ADD CONSTRAINT FK_2E1AEEF5BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_blog ADD CONSTRAINT FK_2E1AEEF5DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire_blog DROP FOREIGN KEY FK_29ED9511DAE07E97');
        $this->addSql('ALTER TABLE tag_blog DROP FOREIGN KEY FK_2E1AEEF5DAE07E97');
        $this->addSql('ALTER TABLE tag_blog DROP FOREIGN KEY FK_2E1AEEF5BAD26311');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE commentaire_blog');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_blog');
    }
}
