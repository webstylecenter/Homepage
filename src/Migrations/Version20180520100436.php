<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180520100436 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed_item DROP FOREIGN KEY FK_9F8CCE498ACBCDEB');
        $this->addSql('DROP INDEX IDX_9F8CCE498ACBCDEB ON feed_item');
        $this->addSql('ALTER TABLE feed_item CHANGE feed_id_id feed_id INT NOT NULL');
        $this->addSql('ALTER TABLE feed_item ADD CONSTRAINT FK_9F8CCE4951A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id)');
        $this->addSql('CREATE INDEX IDX_9F8CCE4951A5BC03 ON feed_item (feed_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed_item DROP FOREIGN KEY FK_9F8CCE4951A5BC03');
        $this->addSql('DROP INDEX IDX_9F8CCE4951A5BC03 ON feed_item');
        $this->addSql('ALTER TABLE feed_item CHANGE feed_id feed_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE feed_item ADD CONSTRAINT FK_9F8CCE498ACBCDEB FOREIGN KEY (feed_id_id) REFERENCES feed (id)');
        $this->addSql('CREATE INDEX IDX_9F8CCE498ACBCDEB ON feed_item (feed_id_id)');
    }
}
