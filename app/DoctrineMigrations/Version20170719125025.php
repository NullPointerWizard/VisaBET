<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170719125025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact (id_contact INT AUTO_INCREMENT NOT NULL, id_organisme INT DEFAULT NULL, id_role SMALLINT DEFAULT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, mail VARCHAR(100) DEFAULT NULL, tel VARCHAR(20) DEFAULT NULL, INDEX IDX_4C62E6385D3AF914 (id_organisme), INDEX IDX_4C62E638DC499668 (id_role), PRIMARY KEY(id_contact)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_contact (id_role SMALLINT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(40) NOT NULL, PRIMARY KEY(id_role)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6385D3AF914 FOREIGN KEY (id_organisme) REFERENCES organismes (id_organisme)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638DC499668 FOREIGN KEY (id_role) REFERENCES role_contact (id_role)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638DC499668');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE role_contact');
    }
}
