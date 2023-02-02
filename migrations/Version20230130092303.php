<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130092303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE presentacion (id INT AUTO_INCREMENT NOT NULL, evento_id INT NOT NULL, juego_id INT NOT NULL, INDEX IDX_56A887B587A5F842 (evento_id), INDEX IDX_56A887B513375255 (juego_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B587A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B513375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B587A5F842');
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B513375255');
        $this->addSql('DROP TABLE presentacion');
    }
}
