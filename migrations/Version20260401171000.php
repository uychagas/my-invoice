<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260401171000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align app_user.default_daily_rate column comment with ORM metadata';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("COMMENT ON COLUMN app_user.default_daily_rate IS ''");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("COMMENT ON COLUMN app_user.default_daily_rate IS '(DC2Type:decimal)'");
    }
}
