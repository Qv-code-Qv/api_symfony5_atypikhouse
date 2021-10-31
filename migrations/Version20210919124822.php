<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210919124822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8AC7EE8B');
        $this->addSql('DROP INDEX IDX_5F9E962A8AC7EE8B ON comments');
        $this->addSql('ALTER TABLE comments CHANGE houses_id ID_booking INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF33DC5DD FOREIGN KEY (ID_booking) REFERENCES booking (ID)');
        $this->addSql('CREATE INDEX comment_id_booking ON comments (ID_booking)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF33DC5DD');
        $this->addSql('DROP INDEX comment_id_booking ON comments');
        $this->addSql('ALTER TABLE comments CHANGE id_booking houses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8AC7EE8B FOREIGN KEY (houses_id) REFERENCES houses (ID)');
        $this->addSql('CREATE INDEX IDX_5F9E962A8AC7EE8B ON comments (houses_id)');
    }
}
