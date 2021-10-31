<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920075445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities DROP FOREIGN KEY FK_B5F1AFE5CFEF7781');
        $this->addSql('ALTER TABLE houses DROP FOREIGN KEY FK_95D7F5CB9F567953');
        $this->addSql('DROP TABLE activities');
        $this->addSql('DROP TABLE activities_types');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE pics');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE tags');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF33DC5DD');
        $this->addSql('DROP INDEX comment_id_booking ON comments');
        $this->addSql('ALTER TABLE comments CHANGE id_booking id_houses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAF252AC FOREIGN KEY (id_houses_id) REFERENCES houses (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AAF252AC ON comments (id_houses_id)');
        $this->addSql('DROP INDEX house_id_category ON houses');
        $this->addSql('ALTER TABLE houses ADD categories VARCHAR(255) NOT NULL, DROP ID_category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activities (ID INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, locationLat DOUBLE PRECISION NOT NULL, locationLng DOUBLE PRECISION NOT NULL, listID_tags VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ID_type INT DEFAULT NULL, INDEX ID_type (ID_type), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE activities_types (ID INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categories (ID INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pics (ID INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE posts (author INT DEFAULT NULL, ID INT AUTO_INCREMENT NOT NULL, publishDate DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ID_house INT DEFAULT NULL, content VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published TINYINT(1) DEFAULT NULL, INDEX post_id_user (author), INDEX post_id_house (ID_house), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tags (ID INT AUTO_INCREMENT NOT NULL, type TINYINT(1) NOT NULL, tag VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE5CFEF7781 FOREIGN KEY (ID_type) REFERENCES activities_types (ID)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFABDAFD8C8 FOREIGN KEY (author) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFABF9B2367 FOREIGN KEY (ID_house) REFERENCES houses (ID)');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAF252AC');
        $this->addSql('DROP INDEX IDX_5F9E962AAF252AC ON comments');
        $this->addSql('ALTER TABLE comments CHANGE id_houses_id ID_booking INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF33DC5DD FOREIGN KEY (ID_booking) REFERENCES booking (ID)');
        $this->addSql('CREATE INDEX comment_id_booking ON comments (ID_booking)');
        $this->addSql('ALTER TABLE houses ADD ID_category INT DEFAULT NULL, DROP categories');
        $this->addSql('ALTER TABLE houses ADD CONSTRAINT FK_95D7F5CB9F567953 FOREIGN KEY (ID_category) REFERENCES categories (ID)');
        $this->addSql('CREATE INDEX house_id_category ON houses (ID_category)');
    }
}
