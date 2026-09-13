<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823163100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Dodaj kolumnę z wartością domyślną
        $this->addSql('ALTER TABLE "user" ADD permissions JSON DEFAULT \'[]\' NOT NULL');

        // Opcjonalnie: usuń wartość domyślną po dodaniu kolumny (jeśli nie chcesz jej mieć w schemacie)
        // $this->addSql('ALTER TABLE "user" ALTER COLUMN permissions DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // Usuń kolumnę
        $this->addSql('ALTER TABLE "user" DROP permissions');
    }
}
