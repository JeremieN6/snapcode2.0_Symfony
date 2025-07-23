<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206093659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Les colonnes service_type_autre et service_type_super_site existent déjà dans la structure initiale
        // Cette migration ne fait rien car les colonnes sont déjà créées
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE more_info_formulaire_controller ADD service_type VARCHAR(255) DEFAULT NULL, DROP service_type_super_site, DROP service_type_autre');
    }
}
