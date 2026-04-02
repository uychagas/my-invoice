<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260402102000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add default daily rate currency to user profile';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user ADD default_daily_rate_currency VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user DROP default_daily_rate_currency');
    }
}
