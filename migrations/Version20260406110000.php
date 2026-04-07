<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260406110000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add local currency field to user profile';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user ADD local_currency VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user DROP local_currency');
    }
}
