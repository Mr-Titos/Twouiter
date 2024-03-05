<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305132414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "userT" (id INT NOT NULL, friend_property_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B189108253519001 ON "userT" (friend_property_id)');
        $this->addSql('ALTER TABLE "userT" ADD CONSTRAINT FK_B189108253519001 FOREIGN KEY (friend_property_id) REFERENCES "userT" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "userT" DROP CONSTRAINT FK_B189108253519001');
        $this->addSql('DROP TABLE "userT"');
    }
}
