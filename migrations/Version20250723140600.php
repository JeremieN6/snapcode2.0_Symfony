<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour créer les tables du blog
 */
final class Version20250723140600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables pour le système de blog (Posts, Categories, Keywords, Comments)';
    }

    public function up(Schema $schema): void
    {
        // Création de la table categories
        $this->addSql('CREATE TABLE categories (
            id INT AUTO_INCREMENT NOT NULL, 
            parent_id INT DEFAULT NULL, 
            name VARCHAR(60) DEFAULT NULL, 
            slug VARCHAR(60) DEFAULT NULL, 
            description LONGTEXT DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL, 
            INDEX IDX_3AF34668727ACA70 (parent_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table keywords
        $this->addSql('CREATE TABLE keywords (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(50) DEFAULT NULL, 
            slug VARCHAR(60) DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table posts
        $this->addSql('CREATE TABLE posts (
            id INT AUTO_INCREMENT NOT NULL, 
            users_id INT NOT NULL, 
            title VARCHAR(255) DEFAULT NULL, 
            slug VARCHAR(255) DEFAULT NULL, 
            content LONGTEXT DEFAULT NULL, 
            featured_image VARCHAR(255) DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            is_favorite TINYINT(1) DEFAULT NULL, 
            meta_description LONGTEXT DEFAULT NULL, 
            meta_title VARCHAR(255) DEFAULT NULL, 
            is_published TINYINT(1) DEFAULT NULL, 
            INDEX IDX_885DBAFA67B3B43D (users_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table comments
        $this->addSql('CREATE TABLE comments (
            id INT AUTO_INCREMENT NOT NULL, 
            parent_id INT DEFAULT NULL, 
            users_id INT NOT NULL, 
            posts_id INT DEFAULT NULL, 
            content LONGTEXT DEFAULT NULL, 
            is_reply TINYINT(1) DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL, 
            is_approved TINYINT(1) DEFAULT NULL, 
            INDEX IDX_5F9E962A727ACA70 (parent_id), 
            INDEX IDX_5F9E962A67B3B43D (users_id), 
            INDEX IDX_5F9E962AD5E258C5 (posts_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table de liaison posts_categories
        $this->addSql('CREATE TABLE posts_categories (
            posts_id INT NOT NULL, 
            categories_id INT NOT NULL, 
            INDEX IDX_A8C3AA54D5E258C5 (posts_id), 
            INDEX IDX_A8C3AA54A21214B7 (categories_id), 
            PRIMARY KEY(posts_id, categories_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Création de la table de liaison posts_keywords
        $this->addSql('CREATE TABLE posts_keywords (
            posts_id INT NOT NULL, 
            keywords_id INT NOT NULL, 
            INDEX IDX_A8C3AA54D5E258C5 (posts_id), 
            INDEX IDX_A8C3AA54B3F0E3C3 (keywords_id), 
            PRIMARY KEY(posts_id, keywords_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Ajout des contraintes de clés étrangères
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts_categories ADD CONSTRAINT FK_A8C3AA54D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_categories ADD CONSTRAINT FK_A8C3AA54A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_keywords ADD CONSTRAINT FK_A8C3AA54D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_keywords ADD CONSTRAINT FK_A8C3AA54B3F0E3C3 FOREIGN KEY (keywords_id) REFERENCES keywords (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Suppression des contraintes de clés étrangères
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA67B3B43D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A67B3B43D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD5E258C5');
        $this->addSql('ALTER TABLE posts_categories DROP FOREIGN KEY FK_A8C3AA54D5E258C5');
        $this->addSql('ALTER TABLE posts_categories DROP FOREIGN KEY FK_A8C3AA54A21214B7');
        $this->addSql('ALTER TABLE posts_keywords DROP FOREIGN KEY FK_A8C3AA54D5E258C5');
        $this->addSql('ALTER TABLE posts_keywords DROP FOREIGN KEY FK_A8C3AA54B3F0E3C3');

        // Suppression des tables
        $this->addSql('DROP TABLE posts_keywords');
        $this->addSql('DROP TABLE posts_categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE keywords');
        $this->addSql('DROP TABLE categories');
    }
}
