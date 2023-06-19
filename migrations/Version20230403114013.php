<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403114013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB02B027A76ED395 ON opinion (user_id)');
        $this->addSql('ALTER TABLE reserva ADD usuario_id INT DEFAULT NULL, ADD restaurante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B38B81E49 FOREIGN KEY (restaurante_id) REFERENCES restaurante (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BDB38439E ON reserva (usuario_id)');
        $this->addSql('CREATE INDEX IDX_188D2E3B38B81E49 ON reserva (restaurante_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395');
        $this->addSql('DROP INDEX IDX_AB02B027A76ED395 ON opinion');
        $this->addSql('ALTER TABLE opinion DROP user_id');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BDB38439E');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B38B81E49');
        $this->addSql('DROP INDEX IDX_188D2E3BDB38439E ON reserva');
        $this->addSql('DROP INDEX IDX_188D2E3B38B81E49 ON reserva');
        $this->addSql('ALTER TABLE reserva DROP usuario_id, DROP restaurante_id');
    }
}
