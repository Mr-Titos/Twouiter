<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307151104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE twouit (id INT NOT NULL, user_id INT NOT NULL, msg_content VARCHAR(511) NOT NULL, entry_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B8ABFA8EA76ED395 ON twouit (user_id)');
        $this->addSql('CREATE TABLE "userT" (id INT NOT NULL, friend_property_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, description VARCHAR(511) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B189108253519001 ON "userT" (friend_property_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN ON "userT" (login)');
        $this->addSql('ALTER TABLE twouit ADD CONSTRAINT FK_B8ABFA8EA76ED395 FOREIGN KEY (user_id) REFERENCES "userT" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "userT" ADD CONSTRAINT FK_B189108253519001 FOREIGN KEY (friend_property_id) REFERENCES "userT" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE twouit DROP CONSTRAINT FK_B8ABFA8EA76ED395');
        $this->addSql('ALTER TABLE "userT" DROP CONSTRAINT FK_B189108253519001');
        $this->addSql('DROP TABLE twouit');
        $this->addSql('DROP TABLE "userT"');
    }
}
