<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920062115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE posts');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posts (author INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, publishDate DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ID_house INT DEFAULT NULL, content VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published TINYINT(1) DEFAULT NULL, INDEX post_id_user (author), INDEX post_id_house (ID_house), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFABDAFD8C8 FOREIGN KEY (author) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFABF9B2367 FOREIGN KEY (ID_house) REFERENCES houses (ID)');
    }
}
