<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504135244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_brand (category_id INT NOT NULL, brand_id INT NOT NULL, INDEX IDX_F461B61112469DE2 (category_id), INDEX IDX_F461B61144F5D008 (brand_id), PRIMARY KEY(category_id, brand_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_brand ADD CONSTRAINT FK_F461B61112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_brand ADD CONSTRAINT FK_F461B61144F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F95812469DE2');
        $this->addSql('DROP INDEX IDX_1C52F95812469DE2 ON brand');
        $this->addSql('ALTER TABLE brand DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_brand DROP FOREIGN KEY FK_F461B61112469DE2');
        $this->addSql('ALTER TABLE category_brand DROP FOREIGN KEY FK_F461B61144F5D008');
        $this->addSql('DROP TABLE category_brand');
        $this->addSql('ALTER TABLE brand ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F95812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_1C52F95812469DE2 ON brand (category_id)');
    }
}
