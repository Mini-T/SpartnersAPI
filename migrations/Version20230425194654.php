<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425194654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AADF6E65AD');
        $this->addSql('DROP INDEX IDX_659DF2AADF6E65AD ON chat');
        $this->addSql('ALTER TABLE chat CHANGE admin_id_id admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA642B8210 ON chat (admin_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F80BDB5F');
        $this->addSql('DROP INDEX IDX_8D93D649F80BDB5F ON user');
        $this->addSql('ALTER TABLE user CHANGE sports_hall_id_id sports_hall_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497480A0A6 FOREIGN KEY (sports_hall_id) REFERENCES sports_hall (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6497480A0A6 ON user (sports_hall_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA642B8210');
        $this->addSql('DROP INDEX IDX_659DF2AA642B8210 ON chat');
        $this->addSql('ALTER TABLE chat CHANGE admin_id admin_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AADF6E65AD FOREIGN KEY (admin_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AADF6E65AD ON chat (admin_id_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497480A0A6');
        $this->addSql('DROP INDEX IDX_8D93D6497480A0A6 ON user');
        $this->addSql('ALTER TABLE user CHANGE sports_hall_id sports_hall_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F80BDB5F FOREIGN KEY (sports_hall_id_id) REFERENCES sports_hall (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F80BDB5F ON user (sports_hall_id_id)');
    }
}
