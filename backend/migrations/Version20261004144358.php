<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261004144358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY (uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EMPLOYEE_EMAIL ON employee (email)');
        $this->addSql('ALTER TABLE "user" ALTER permissions DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employee');
        $this->addSql('ALTER TABLE "user" ALTER permissions SET DEFAULT \'[]\'');
    }
}
