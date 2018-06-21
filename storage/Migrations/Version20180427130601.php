<?php

namespace Storage\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180427130601 extends AbstractMigration
{
    /**
     * Get migration description
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create table oauth_clients if not exists';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS `oauth_clients` (
                `id` VARCHAR(36) NOT NULL,
                `client_secret` VARCHAR(80) NOT NULL,
                `is_public` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`))
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS oauth_clients');
    }
}
