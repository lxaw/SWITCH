<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209183223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food ADD total_energy_amount DOUBLE PRECISION DEFAULT NULL, ADD total_fiber_amount DOUBLE PRECISION DEFAULT NULL, ADD total_potassium_amount DOUBLE PRECISION DEFAULT NULL, ADD total_fat_amount DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food DROP total_energy_amount, DROP total_fiber_amount, DROP total_potassium_amount, DROP total_fat_amount');
    }
}
