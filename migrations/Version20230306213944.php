<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306213944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD sports_hall_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F80BDB5F FOREIGN KEY (sports_hall_id_id) REFERENCES sports_hall (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F80BDB5F ON user (sports_hall_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F80BDB5F');
        $this->addSql('DROP INDEX IDX_8D93D649F80BDB5F ON user');
        $this->addSql('ALTER TABLE user DROP sports_hall_id_id');
    }
}
