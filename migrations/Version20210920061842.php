<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920061842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF33DC5DD');
        $this->addSql('DROP INDEX comment_id_booking ON comments');
        $this->addSql('ALTER TABLE comments DROP ID_booking');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD ID_booking INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF33DC5DD FOREIGN KEY (ID_booking) REFERENCES booking (ID)');
        $this->addSql('CREATE INDEX comment_id_booking ON comments (ID_booking)');
    }
}
