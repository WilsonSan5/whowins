<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031165609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB03412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_937AB03412469DE2 ON `character` (category_id)');
        $this->addSql('ALTER TABLE votes ADD fight_id INT DEFAULT NULL, ADD fighter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF34934341 FOREIGN KEY (fighter_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_518B7ACFAC6657E4 ON votes (fight_id)');
        $this->addSql('CREATE INDEX IDX_518B7ACF34934341 ON votes (fighter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB03412469DE2');
        $this->addSql('DROP INDEX IDX_937AB03412469DE2 ON `character`');
        $this->addSql('ALTER TABLE `character` DROP category_id');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFAC6657E4');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF34934341');
        $this->addSql('DROP INDEX IDX_518B7ACFAC6657E4 ON votes');
        $this->addSql('DROP INDEX IDX_518B7ACF34934341 ON votes');
        $this->addSql('ALTER TABLE votes DROP fight_id, DROP fighter_id');
    }
}
