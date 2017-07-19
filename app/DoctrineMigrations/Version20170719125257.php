<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170719125257 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sur_liste_diffusion (id_lot INT NOT NULL, id_contact INT NOT NULL, INDEX IDX_9A66E509525C141 (id_lot), INDEX IDX_9A66E5092FF4F48 (id_contact), PRIMARY KEY(id_lot, id_contact)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sur_liste_diffusion ADD CONSTRAINT FK_9A66E509525C141 FOREIGN KEY (id_lot) REFERENCES lots (id_lot)');
        $this->addSql('ALTER TABLE sur_liste_diffusion ADD CONSTRAINT FK_9A66E5092FF4F48 FOREIGN KEY (id_contact) REFERENCES contact (id_contact)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sur_liste_diffusion');
    }
}
