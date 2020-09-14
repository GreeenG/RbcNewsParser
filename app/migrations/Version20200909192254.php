<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909192254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE rbc_news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rbc_news (
            id INT NOT NULL,
            original_url VARCHAR(1000) NOT NULL,
            title VARCHAR(1000) NOT NULL,
            original_image_url VARCHAR(1000) DEFAULT NULL,
            image_url VARCHAR(1000) DEFAULT NULL,
            image_title VARCHAR(1000) DEFAULT NULL, 
            content VARCHAR(100000) NOT NULL,
            timestamp TIMESTAMP(10) NOT NULL, 
            PRIMARY KEY(id))'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE rbc_news_id_seq CASCADE');
        $this->addSql('DROP TABLE rbc_news');
    }
}
