<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212211448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_catg VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, date_cour DATE NOT NULL, temps_cour TIME NOT NULL, titre_cour VARCHAR(30) NOT NULL, INDEX IDX_FDCA8C9CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, nom_form VARCHAR(50) NOT NULL, duree_form INT DEFAULT NULL, description_form LONGTEXT DEFAULT NULL, INDEX IDX_404021BFBCF5E72D (categorie_id), INDEX IDX_404021BFFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom_mat VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_cours (matiere_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_716A22E9F46CD258 (matiere_id), INDEX IDX_716A22E97ECF78B0 (cours_id), PRIMARY KEY(matiere_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_activite (utilisateur_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_A60EAC8AFB88E14F (utilisateur_id), INDEX IDX_A60EAC8A9B0F88B1 (activite_id), PRIMARY KEY(utilisateur_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E97ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_activite ADD CONSTRAINT FK_A60EAC8AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_activite ADD CONSTRAINT FK_A60EAC8A9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CFB88E14F');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFBCF5E72D');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFFB88E14F');
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E9F46CD258');
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E97ECF78B0');
        $this->addSql('ALTER TABLE utilisateur_activite DROP FOREIGN KEY FK_A60EAC8AFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_activite DROP FOREIGN KEY FK_A60EAC8A9B0F88B1');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_cours');
        $this->addSql('DROP TABLE utilisateur_activite');
    }
}
