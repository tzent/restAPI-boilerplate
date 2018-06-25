<?php

namespace Storage\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180430082302 extends AbstractMigration
{
    /**
     * Get migration description
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create table jwts if not exists';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS `jwts` (
                `id` VARCHAR(36) NOT NULL,
                `client_id` VARCHAR(80) NOT NULL,
                `subject` VARCHAR(80) NULL,
                `audience` VARCHAR(40) NULL,
                `private_key` TEXT NULL,
            PRIMARY KEY (`id`),
            INDEX `client_idx` (`client_id` ASC),
            CONSTRAINT `jwt_client`
                FOREIGN KEY (`client_id`)
                REFERENCES `oauth_clients` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS jwts');
    }
}
