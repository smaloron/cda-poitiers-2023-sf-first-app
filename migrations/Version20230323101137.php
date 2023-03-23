<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323101137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teacher_skill (teacher_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_FC2582A41807E1D (teacher_id), INDEX IDX_FC2582A5585C142 (skill_id), PRIMARY KEY(teacher_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, skill_name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT FK_FC2582A41807E1D FOREIGN KEY (teacher_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT FK_FC2582A5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher_skill DROP FOREIGN KEY FK_FC2582A41807E1D');
        $this->addSql('ALTER TABLE teacher_skill DROP FOREIGN KEY FK_FC2582A5585C142');
        $this->addSql('DROP TABLE teacher_skill');
        $this->addSql('DROP TABLE skill');
    }
}
