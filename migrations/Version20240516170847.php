<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516170847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_lesson (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_9D266FCECB944F1A (student_id), INDEX IDX_9D266FCE41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCECB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCE41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCECB944F1A');
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCE41807E1D');
        $this->addSql('DROP TABLE user_lesson');
    }
}
