<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250924072012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX uniq_coupons_code_is_active ON coupons (code, is_active)');
        $this->addSql('ALTER TABLE tax_rates ADD mask VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE tax_rates ALTER rate TYPE NUMERIC(5, 2)');
        $this->addSql('CREATE UNIQUE INDEX uniq_country_code_rate ON tax_rates (country, mask)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_coupons_code_is_active');
        $this->addSql('DROP INDEX uniq_country_code_rate');
        $this->addSql('ALTER TABLE tax_rates DROP mask');
        $this->addSql('ALTER TABLE tax_rates ALTER rate TYPE NUMERIC(5, 4)');
    }
}
