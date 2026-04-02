<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260401170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add profile fields to user: job description and default daily rate';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user ADD job_description VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD default_daily_rate NUMERIC(12, 2) DEFAULT NULL');
        $this->addSql("COMMENT ON COLUMN app_user.default_daily_rate IS '(DC2Type:decimal)'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_user DROP job_description');
        $this->addSql('ALTER TABLE app_user DROP default_daily_rate');
    }
}
