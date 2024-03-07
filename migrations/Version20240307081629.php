<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307081629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE twouit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE twouit (id INT NOT NULL, owner_id INT NOT NULL, msg_content VARCHAR(511) NOT NULL, entry_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B8ABFA8E7E3C61F9 ON twouit (owner_id)');
        $this->addSql('ALTER TABLE twouit ADD CONSTRAINT FK_B8ABFA8E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "userT" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE twouit_id_seq CASCADE');
        $this->addSql('ALTER TABLE twouit DROP CONSTRAINT FK_B8ABFA8E7E3C61F9');
        $this->addSql('DROP TABLE twouit');
    }
}
