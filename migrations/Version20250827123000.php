<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250827123000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_headline column to posts';
    }

    public function up(Schema $schema): void
    {
        // add nullable boolean column for headline posts
        $this->addSql('ALTER TABLE posts ADD is_headline TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE posts DROP is_headline');
    }
}
