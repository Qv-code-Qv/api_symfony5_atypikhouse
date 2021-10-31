<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920061441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD id_houses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAF252AC FOREIGN KEY (id_houses_id) REFERENCES houses (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AAF252AC ON comments (id_houses_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAF252AC');
        $this->addSql('DROP INDEX IDX_5F9E962AAF252AC ON comments');
        $this->addSql('ALTER TABLE comments DROP id_houses_id');
    }
}
