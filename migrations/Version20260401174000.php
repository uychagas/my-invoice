<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260401174000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reference month to invoices';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invoice ADD reference_month VARCHAR(7) DEFAULT NULL');
        $this->addSql("UPDATE invoice SET reference_month = TO_CHAR(issue_date, 'YYYY-MM') WHERE reference_month IS NULL");
        $this->addSql('ALTER TABLE invoice ALTER reference_month SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invoice DROP reference_month');
    }
}
