<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216172852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD writer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C01551431BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C01551431BC7E6B6 ON blog (writer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C01551431BC7E6B6');
        $this->addSql('DROP INDEX IDX_C01551431BC7E6B6 ON blog');
        $this->addSql('ALTER TABLE blog DROP writer_id');
    }
}
