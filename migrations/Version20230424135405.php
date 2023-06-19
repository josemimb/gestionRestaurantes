<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424135405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesa ADD restaurante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mesa ADD CONSTRAINT FK_98B382F238B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id)');
        $this->addSql('CREATE INDEX IDX_98B382F238B81E49 ON mesa (restaurante_id)');
        $this->addSql('ALTER TABLE opinion ADD restaurante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B02738B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id)');
        $this->addSql('CREATE INDEX IDX_AB02B02738B81E49 ON opinion (restaurante_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesa DROP FOREIGN KEY FK_98B382F238B81E49');
        $this->addSql('DROP INDEX IDX_98B382F238B81E49 ON mesa');
        $this->addSql('ALTER TABLE mesa DROP restaurante_id');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B02738B81E49');
        $this->addSql('DROP INDEX IDX_AB02B02738B81E49 ON opinion');
        $this->addSql('ALTER TABLE opinion DROP restaurante_id');
    }
}
