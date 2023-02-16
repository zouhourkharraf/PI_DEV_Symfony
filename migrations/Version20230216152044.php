<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216152044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515C54C8C93');
        $this->addSql('DROP INDEX IDX_B8755515C54C8C93 ON activite');
        $this->addSql('ALTER TABLE activite CHANGE typeid type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_B8755515C54C8C93 ON activite (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515C54C8C93');
        $this->addSql('DROP INDEX IDX_B8755515C54C8C93 ON activite');
        $this->addSql('ALTER TABLE activite CHANGE type_id typeid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515C54C8C93 FOREIGN KEY (typeid) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_B8755515C54C8C93 ON activite (typeid)');
    }
}
