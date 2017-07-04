<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170704085621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items CHANGE id_lot id_lot INT NOT NULL');
        $this->addSql('ALTER TABLE lots CHANGE id_affaire id_affaire INT NOT NULL');
        $this->addSql('ALTER TABLE remarques_visa CHANGE id_visa id_visa INT NOT NULL');
        $this->addSql('ALTER TABLE visas CHANGE id_item id_item INT NOT NULL, CHANGE vise_par vise_par INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items CHANGE id_lot id_lot INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lots CHANGE id_affaire id_affaire INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remarques_visa CHANGE id_visa id_visa INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visas CHANGE id_item id_item INT DEFAULT NULL, CHANGE vise_par vise_par INT DEFAULT NULL');
    }
}
