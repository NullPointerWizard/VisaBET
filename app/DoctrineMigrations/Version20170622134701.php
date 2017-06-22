<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170622134701 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE affaires (id_affaire INT AUTO_INCREMENT NOT NULL, id_organisme INT DEFAULT NULL, year SMALLINT NOT NULL, numero_affaire INT NOT NULL, nom_affaire VARCHAR(100) NOT NULL, date_butoir DATE DEFAULT NULL, travail_a_effectuer VARCHAR(300) NOT NULL, INDEX id_organisme_fk_affaires (id_organisme), PRIMARY KEY(id_affaire)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id_document INT AUTO_INCREMENT NOT NULL, id_affaire INT DEFAULT NULL, type VARCHAR(10) NOT NULL, filename VARCHAR(150) NOT NULL, date_reception DATE NOT NULL, date_limite_visa DATE NOT NULL, etat TINYINT(1) NOT NULL, path VARCHAR(2083) NOT NULL, INDEX documents_affaires_fk (id_affaire), PRIMARY KEY(id_document)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items (id_item INT AUTO_INCREMENT NOT NULL, id_lot INT DEFAULT NULL, type VARCHAR(10) NOT NULL, nom_item VARCHAR(50) NOT NULL, INDEX lots_item_fk (id_lot), PRIMARY KEY(id_item)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_materiel (id_item INT NOT NULL, ensemble VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_6FA9005F943B391C (id_item), PRIMARY KEY(id_item)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_ndc (id_item INT NOT NULL, UNIQUE INDEX UNIQ_E9483E7D943B391C (id_item), PRIMARY KEY(id_item)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_plans (id_item INT NOT NULL, etage VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_31BB526A943B391C (id_item), PRIMARY KEY(id_item)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lots (id_lot INT AUTO_INCREMENT NOT NULL, id_affaire INT DEFAULT NULL, id_nom_lot SMALLINT DEFAULT NULL, numero_lot SMALLINT NOT NULL, INDEX noms_lots_lots_fk (id_nom_lot), INDEX affaires_lots_fk (id_affaire), PRIMARY KEY(id_lot)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noms_lots (id_nom_lot SMALLINT AUTO_INCREMENT NOT NULL, nom_lot VARCHAR(40) NOT NULL, PRIMARY KEY(id_nom_lot)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organismes (id_organisme INT AUTO_INCREMENT NOT NULL, nom_organisme VARCHAR(50) NOT NULL, PRIMARY KEY(id_organisme)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remarques_visa (no_remarque INT NOT NULL, id_item INT DEFAULT NULL, version TINYINT(1) DEFAULT NULL, id_type_remarque SMALLINT DEFAULT NULL, remarque VARCHAR(300) NOT NULL, UNIQUE INDEX UNIQ_C69B6E98943B391C (id_item), UNIQUE INDEX UNIQ_C69B6E98BF1CD3C3 (version), INDEX types_remarque_remarques_visa_fk (id_type_remarque), INDEX index_visa (id_item, version), INDEX index_version (version), INDEX IDX_C69B6E98943B391C (id_item), PRIMARY KEY(no_remarque)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types_remarque (id_type_remarque SMALLINT AUTO_INCREMENT NOT NULL, type_remarque VARCHAR(50) NOT NULL, PRIMARY KEY(id_type_remarque)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id_utilisateur INT AUTO_INCREMENT NOT NULL, id_organisme INT DEFAULT NULL, statut VARCHAR(10) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, mail VARCHAR(100) DEFAULT NULL, tel VARCHAR(20) DEFAULT NULL, INDEX id_organisme_fk_utilisateur (id_organisme), PRIMARY KEY(id_utilisateur)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_on (id_utilisateur INT NOT NULL, id_affaire INT NOT NULL, INDEX IDX_DBB1D4350EAE44 (id_utilisateur), INDEX IDX_DBB1D4342A2B19F (id_affaire), PRIMARY KEY(id_utilisateur, id_affaire)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visas (version TINYINT(1) NOT NULL, id_item INT NOT NULL, id_document INT DEFAULT NULL, vise_par INT DEFAULT NULL, date_emission DATE NOT NULL, etat_visa VARCHAR(9) NOT NULL, indice_plan VARCHAR(4) NOT NULL, UNIQUE INDEX UNIQ_15C3F692943B391C (id_item), INDEX IDX_15C3F692C825F448 (vise_par), INDEX documents_visas_fk (id_document), INDEX index_version (version), INDEX index_id (id_item), PRIMARY KEY(version, id_item)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affaires ADD CONSTRAINT FK_2B270FFA5D3AF914 FOREIGN KEY (id_organisme) REFERENCES organismes (id_organisme)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B0728842A2B19F FOREIGN KEY (id_affaire) REFERENCES affaires (id_affaire)');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D9525C141 FOREIGN KEY (id_lot) REFERENCES lots (id_lot)');
        $this->addSql('ALTER TABLE items_materiel ADD CONSTRAINT FK_6FA9005F943B391C FOREIGN KEY (id_item) REFERENCES items (id_item)');
        $this->addSql('ALTER TABLE items_ndc ADD CONSTRAINT FK_E9483E7D943B391C FOREIGN KEY (id_item) REFERENCES items (id_item)');
        $this->addSql('ALTER TABLE items_plans ADD CONSTRAINT FK_31BB526A943B391C FOREIGN KEY (id_item) REFERENCES items (id_item)');
        $this->addSql('ALTER TABLE lots ADD CONSTRAINT FK_916087CE42A2B19F FOREIGN KEY (id_affaire) REFERENCES affaires (id_affaire)');
        $this->addSql('ALTER TABLE lots ADD CONSTRAINT FK_916087CE67D6A038 FOREIGN KEY (id_nom_lot) REFERENCES noms_lots (id_nom_lot)');
        $this->addSql('ALTER TABLE remarques_visa ADD CONSTRAINT FK_C69B6E98943B391C FOREIGN KEY (id_item) REFERENCES visas (id_item)');
        $this->addSql('ALTER TABLE remarques_visa ADD CONSTRAINT FK_C69B6E98BF1CD3C3 FOREIGN KEY (version) REFERENCES visas (version)');
        $this->addSql('ALTER TABLE remarques_visa ADD CONSTRAINT FK_C69B6E98CAA3EC32 FOREIGN KEY (id_type_remarque) REFERENCES types_remarque (id_type_remarque)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B35D3AF914 FOREIGN KEY (id_organisme) REFERENCES organismes (id_organisme)');
        $this->addSql('ALTER TABLE work_on ADD CONSTRAINT FK_DBB1D4350EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE work_on ADD CONSTRAINT FK_DBB1D4342A2B19F FOREIGN KEY (id_affaire) REFERENCES affaires (id_affaire)');
        $this->addSql('ALTER TABLE visas ADD CONSTRAINT FK_15C3F692943B391C FOREIGN KEY (id_item) REFERENCES items (id_item)');
        $this->addSql('ALTER TABLE visas ADD CONSTRAINT FK_15C3F69288B266E3 FOREIGN KEY (id_document) REFERENCES documents (id_document)');
        $this->addSql('ALTER TABLE visas ADD CONSTRAINT FK_15C3F692C825F448 FOREIGN KEY (vise_par) REFERENCES utilisateur (id_utilisateur)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B0728842A2B19F');
        $this->addSql('ALTER TABLE lots DROP FOREIGN KEY FK_916087CE42A2B19F');
        $this->addSql('ALTER TABLE work_on DROP FOREIGN KEY FK_DBB1D4342A2B19F');
        $this->addSql('ALTER TABLE visas DROP FOREIGN KEY FK_15C3F69288B266E3');
        $this->addSql('ALTER TABLE items_materiel DROP FOREIGN KEY FK_6FA9005F943B391C');
        $this->addSql('ALTER TABLE items_ndc DROP FOREIGN KEY FK_E9483E7D943B391C');
        $this->addSql('ALTER TABLE items_plans DROP FOREIGN KEY FK_31BB526A943B391C');
        $this->addSql('ALTER TABLE visas DROP FOREIGN KEY FK_15C3F692943B391C');
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D9525C141');
        $this->addSql('ALTER TABLE lots DROP FOREIGN KEY FK_916087CE67D6A038');
        $this->addSql('ALTER TABLE affaires DROP FOREIGN KEY FK_2B270FFA5D3AF914');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B35D3AF914');
        $this->addSql('ALTER TABLE remarques_visa DROP FOREIGN KEY FK_C69B6E98CAA3EC32');
        $this->addSql('ALTER TABLE work_on DROP FOREIGN KEY FK_DBB1D4350EAE44');
        $this->addSql('ALTER TABLE visas DROP FOREIGN KEY FK_15C3F692C825F448');
        $this->addSql('ALTER TABLE remarques_visa DROP FOREIGN KEY FK_C69B6E98943B391C');
        $this->addSql('ALTER TABLE remarques_visa DROP FOREIGN KEY FK_C69B6E98BF1CD3C3');
        $this->addSql('DROP TABLE affaires');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE items_materiel');
        $this->addSql('DROP TABLE items_ndc');
        $this->addSql('DROP TABLE items_plans');
        $this->addSql('DROP TABLE lots');
        $this->addSql('DROP TABLE noms_lots');
        $this->addSql('DROP TABLE organismes');
        $this->addSql('DROP TABLE remarques_visa');
        $this->addSql('DROP TABLE types_remarque');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE work_on');
        $this->addSql('DROP TABLE visas');
    }
}
