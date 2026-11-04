<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261104213000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a1a76ed395');
        $this->addSql('DROP INDEX idx_5d9f75a1a76ed395');
        $this->addSql('ALTER TABLE employee RENAME COLUMN user_id TO created_by');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_5D9F75A1DE12AB56 ON employee (created_by)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1DE12AB56');
        $this->addSql('DROP INDEX IDX_5D9F75A1DE12AB56');
        $this->addSql('ALTER TABLE employee RENAME COLUMN created_by TO user_id');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a1a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5d9f75a1a76ed395 ON employee (user_id)');
    }
}
