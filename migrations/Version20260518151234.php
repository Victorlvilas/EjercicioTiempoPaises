<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518151234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capital_weather_measurement (id INT AUTO_INCREMENT NOT NULL, temperature DOUBLE PRECISION DEFAULT NULL, weather_lat DOUBLE PRECISION DEFAULT NULL, weather_lng DOUBLE PRECISION DEFAULT NULL, measured_at DATETIME NOT NULL, country_id INT DEFAULT NULL, INDEX IDX_F5C748E4F92F3E70 (country_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, code VARCHAR(2) NOT NULL, capital VARCHAR(255) DEFAULT NULL, population INT DEFAULT NULL, country_lat DOUBLE PRECISION DEFAULT NULL, country_lng DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_5373C96677153098 (code), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE capital_weather_measurement ADD CONSTRAINT FK_F5C748E4F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capital_weather_measurement DROP FOREIGN KEY FK_F5C748E4F92F3E70');
        $this->addSql('DROP TABLE capital_weather_measurement');
        $this->addSql('DROP TABLE country');
    }
}
