<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526082846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE social_network ADD brand_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network ADD CONSTRAINT FK_EFFF522124BD5740 FOREIGN KEY (brand_id_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_EFFF522124BD5740 ON social_network (brand_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE social_network DROP FOREIGN KEY FK_EFFF522124BD5740');
        $this->addSql('DROP INDEX IDX_EFFF522124BD5740 ON social_network');
        $this->addSql('ALTER TABLE social_network DROP brand_id_id');
    }
}
