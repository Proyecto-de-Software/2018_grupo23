<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319234932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consulta CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE motivo_id motivo_id INT DEFAULT NULL, CHANGE tratamiento_farmacologico_id tratamiento_farmacologico_id INT DEFAULT NULL, CHANGE acompanamiento_id acompanamiento_id INT DEFAULT NULL, CHANGE internacion internacion TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE institucion CHANGE tipo_institucion_id tipo_institucion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE genero_id genero_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rol_tiene_permiso RENAME INDEX fk_permiso_id TO IDX_6367C98B6CEFAD37');
        $this->addSql('ALTER TABLE usuario DROP username, DROP updated_at, DROP created_at, CHANGE email email VARCHAR(180) NOT NULL, CHANGE activo activo SMALLINT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
        $this->addSql('ALTER TABLE usuario_tiene_rol RENAME INDEX fk_rol_utp_id TO IDX_D501DB714BAB96C');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE consulta CHANGE acompanamiento_id acompanamiento_id INT NOT NULL, CHANGE motivo_id motivo_id INT NOT NULL, CHANGE paciente_id paciente_id INT NOT NULL, CHANGE tratamiento_farmacologico_id tratamiento_farmacologico_id INT NOT NULL, CHANGE internacion internacion TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE institucion CHANGE tipo_institucion_id tipo_institucion_id INT NOT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE genero_id genero_id INT NOT NULL');
        $this->addSql('ALTER TABLE rol_tiene_permiso RENAME INDEX idx_6367c98b6cefad37 TO FK_permiso_id');
        $this->addSql('DROP INDEX UNIQ_2265B05DE7927C74 ON usuario');
        $this->addSql('ALTER TABLE usuario ADD username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE activo activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE usuario_tiene_rol RENAME INDEX idx_d501db714bab96c TO fk_rol_utp_id');
    }
}
