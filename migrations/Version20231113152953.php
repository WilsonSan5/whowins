<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113152953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fight (id INT AUTO_INCREMENT NOT NULL, is_balanced TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fight_fighter (fight_id INT NOT NULL, fighter_id INT NOT NULL, INDEX IDX_696BBD96AC6657E4 (fight_id), INDEX IDX_696BBD9634934341 (fighter_id), PRIMARY KEY(fight_id, fighter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, strength INT NOT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_7A08C3FC12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, fight_id INT DEFAULT NULL, fighter_id INT DEFAULT NULL, number_of_votes INT NOT NULL, INDEX IDX_5A108564AC6657E4 (fight_id), INDEX IDX_5A10856434934341 (fighter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fight_fighter ADD CONSTRAINT FK_696BBD96AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight_fighter ADD CONSTRAINT FK_696BBD9634934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856434934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight_fighter DROP FOREIGN KEY FK_696BBD96AC6657E4');
        $this->addSql('ALTER TABLE fight_fighter DROP FOREIGN KEY FK_696BBD9634934341');
        $this->addSql('ALTER TABLE fighter DROP FOREIGN KEY FK_7A08C3FC12469DE2');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564AC6657E4');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856434934341');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE fight_fighter');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE vote');
    }
}
