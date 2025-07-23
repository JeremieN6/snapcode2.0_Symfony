<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(60) DEFAULT NULL, slug VARCHAR(60) DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME DEFAULT NULL, CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3AF34668727ACA70 ON categories (parent_id)');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, users_id INTEGER NOT NULL, posts_id INTEGER DEFAULT NULL, content CLOB DEFAULT NULL, is_reply BOOLEAN DEFAULT NULL, created_at DATETIME DEFAULT NULL, is_approved BOOLEAN DEFAULT NULL, CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5F9E962A727ACA70 ON comments (parent_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A67B3B43D ON comments (users_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AD5E258C5 ON comments (posts_id)');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(125) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_number_company VARCHAR(255) DEFAULT NULL, email_message CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subscription_id INTEGER DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, amount_paid INTEGER DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, hosted_invoice_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_906517449A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_906517449A1887DC ON invoice (subscription_id)');
        $this->addSql('CREATE TABLE keywords (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, slug VARCHAR(60) DEFAULT NULL, created_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE more_info_formulaire_controller (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(125) DEFAULT NULL, prenom VARCHAR(125) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, service_type_super_site VARCHAR(255) DEFAULT NULL, service_type_autre VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE newsletter (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(100) DEFAULT NULL)');
        $this->addSql('CREATE TABLE "plan" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, prix INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL, payment_link VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, users_id INTEGER NOT NULL, title VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, content CLOB DEFAULT NULL, featured_image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_favorite BOOLEAN DEFAULT NULL, meta_description CLOB DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, is_published BOOLEAN DEFAULT NULL, CONSTRAINT FK_885DBAFA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_885DBAFA67B3B43D ON posts (users_id)');
        $this->addSql('CREATE TABLE posts_categories (posts_id INTEGER NOT NULL, categories_id INTEGER NOT NULL, PRIMARY KEY(posts_id, categories_id), CONSTRAINT FK_A8C3AA46D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A8C3AA46A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A8C3AA46D5E258C5 ON posts_categories (posts_id)');
        $this->addSql('CREATE INDEX IDX_A8C3AA46A21214B7 ON posts_categories (categories_id)');
        $this->addSql('CREATE TABLE posts_keywords (posts_id INTEGER NOT NULL, keywords_id INTEGER NOT NULL, PRIMARY KEY(posts_id, keywords_id), CONSTRAINT FK_70906D97D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_70906D976205D0B8 FOREIGN KEY (keywords_id) REFERENCES keywords (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_70906D97D5E258C5 ON posts_keywords (posts_id)');
        $this->addSql('CREATE INDEX IDX_70906D976205D0B8 ON posts_keywords (keywords_id)');
        $this->addSql('CREATE TABLE subscription (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, plan_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL, current_period_start DATETIME NOT NULL, current_period_end DATETIME NOT NULL, is_active BOOLEAN NOT NULL, CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES "plan" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A3C664D3E899029B ON subscription (plan_id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3A76ED395 ON subscription (user_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(125) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(200) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATE DEFAULT NULL, stripe_id VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE keywords');
        $this->addSql('DROP TABLE more_info_formulaire_controller');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE "plan"');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE posts_categories');
        $this->addSql('DROP TABLE posts_keywords');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
