<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180521165249 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE checklist_item (id INT AUTO_INCREMENT NOT NULL, item VARCHAR(255) NOT NULL, checked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE checklistitem');
        $this->addSql('ALTER TABLE feed ADD updated_at DATETIME NOT NULL, CHANGE created created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE note ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE checklistitem (id INT AUTO_INCREMENT NOT NULL, item VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, checked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE checklist_item');
        $this->addSql('ALTER TABLE feed ADD created DATETIME NOT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE note DROP created_at, DROP updated_at');
    }
}
