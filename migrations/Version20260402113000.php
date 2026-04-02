<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260402113000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add email field to company';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company ADD email VARCHAR(180) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company DROP email');
    }
}
