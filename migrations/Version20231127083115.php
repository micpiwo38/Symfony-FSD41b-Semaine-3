<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127083115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C1645DEA9 FOREIGN KEY (reference_id) REFERENCES `references` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE2DDF8C1645DEA9 ON produits (reference_id)');
        $this->addSql('ALTER TABLE `references` ADD produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `references` ADD CONSTRAINT FK_9F1E2D9CCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F1E2D9CCD11A2CF ON `references` (produits_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C1645DEA9');
        $this->addSql('DROP INDEX UNIQ_BE2DDF8C1645DEA9 ON produits');
        $this->addSql('ALTER TABLE `references` DROP FOREIGN KEY FK_9F1E2D9CCD11A2CF');
        $this->addSql('DROP INDEX UNIQ_9F1E2D9CCD11A2CF ON `references`');
        $this->addSql('ALTER TABLE `references` DROP produits_id');
    }
}
