<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228035907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur CHANGE pseudo_util pseudo_util VARCHAR(60) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3B9A56647 ON utilisateur (pseudo_util)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1D1C63B3B9A56647 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur CHANGE pseudo_util pseudo_util VARCHAR(60) DEFAULT NULL');
    }
}
