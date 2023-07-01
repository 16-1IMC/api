<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630121827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand ADD profile_picture_id INT DEFAULT NULL, ADD banner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958292E8AE2 FOREIGN KEY (profile_picture_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958684EC833 FOREIGN KEY (banner_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C52F958292E8AE2 ON brand (profile_picture_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C52F958684EC833 ON brand (banner_id)');
        $this->addSql('ALTER TABLE user ADD plain_password VARCHAR(255) DEFAULT NULL, ADD roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `brand` DROP FOREIGN KEY FK_1C52F958292E8AE2');
        $this->addSql('ALTER TABLE `brand` DROP FOREIGN KEY FK_1C52F958684EC833');
        $this->addSql('DROP INDEX UNIQ_1C52F958292E8AE2 ON `brand`');
        $this->addSql('DROP INDEX UNIQ_1C52F958684EC833 ON `brand`');
        $this->addSql('ALTER TABLE `brand` DROP profile_picture_id, DROP banner_id');
        $this->addSql('ALTER TABLE `user` DROP plain_password, DROP roles');
    }
}
