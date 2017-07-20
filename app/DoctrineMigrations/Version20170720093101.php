<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170720093101 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fiches_visa (id_fiche INT AUTO_INCREMENT NOT NULL, id_lot INT NOT NULL, numero_fiche SMALLINT NOT NULL, filename VARCHAR(150) DEFAULT NULL, path VARCHAR(2083) DEFAULT NULL, INDEX IDX_417AF4619525C141 (id_lot), PRIMARY KEY(id_fiche)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiches_visa ADD CONSTRAINT FK_417AF4619525C141 FOREIGN KEY (id_lot) REFERENCES lots (id_lot)');
        $this->addSql('ALTER TABLE documents ADD id_fiche INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A2968D48 FOREIGN KEY (id_fiche) REFERENCES fiches_visa (id_fiche)');
        $this->addSql('CREATE INDEX IDX_A2B07288A2968D48 ON documents (id_fiche)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288A2968D48');
        $this->addSql('DROP TABLE fiches_visa');
        $this->addSql('DROP INDEX IDX_A2B07288A2968D48 ON documents');
        $this->addSql('ALTER TABLE documents DROP id_fiche');
    }
}
