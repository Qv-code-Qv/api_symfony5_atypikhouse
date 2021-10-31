<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920063755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE houses DROP FOREIGN KEY FK_95D7F5CB9F567953');
        $this->addSql('DROP INDEX IDX_95D7F5CB9F567953 ON houses');
        $this->addSql('ALTER TABLE houses DROP ID_category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE houses ADD ID_category INT DEFAULT NULL');
        $this->addSql('ALTER TABLE houses ADD CONSTRAINT FK_95D7F5CB9F567953 FOREIGN KEY (ID_category) REFERENCES categories (ID)');
        $this->addSql('CREATE INDEX IDX_95D7F5CB9F567953 ON houses (ID_category)');
    }
}
