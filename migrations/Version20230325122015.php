<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325122015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD dateact DATE NOT NULL, ADD nbparticipants INT NOT NULL, ADD positionact VARCHAR(80) NOT NULL, DROP date_act, DROP nb_participants, DROP position_act, CHANGE nom_act nomact VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD note_ev DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE type ADD nomtype VARCHAR(20) NOT NULL, DROP nom_type, CHANGE description_type descriptiontype LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD date_act DATE DEFAULT NULL, ADD nb_participants INT DEFAULT NULL, ADD position_act VARCHAR(80) DEFAULT NULL, DROP dateact, DROP nbparticipants, DROP positionact, CHANGE nomact nom_act VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE evenement DROP note_ev');
        $this->addSql('ALTER TABLE type ADD nom_type VARCHAR(20) DEFAULT NULL, DROP nomtype, CHANGE descriptiontype description_type LONGTEXT DEFAULT NULL');
    }
}
