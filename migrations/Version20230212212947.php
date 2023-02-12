<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212212947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E97ECF78B0');
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E9F46CD258');
        $this->addSql('DROP TABLE matiere_cours');
        $this->addSql('ALTER TABLE cours ADD matiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CF46CD258 ON cours (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere_cours (matiere_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_716A22E97ECF78B0 (cours_id), INDEX IDX_716A22E9F46CD258 (matiere_id), PRIMARY KEY(matiere_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E97ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CF46CD258');
        $this->addSql('DROP INDEX IDX_FDCA8C9CF46CD258 ON cours');
        $this->addSql('ALTER TABLE cours DROP matiere_id');
    }
}
