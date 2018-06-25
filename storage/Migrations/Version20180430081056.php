<?php

namespace Storage\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180430081056 extends AbstractMigration
{
    /**
     * Get migration description
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create table access_tokens if not exists';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS `access_tokens` (
                `id` VARCHAR(36) NOT NULL,
                `access_token` VARCHAR(40) NOT NULL,
                `client_id` VARCHAR(80) NOT NULL,
                `user_id` VARCHAR(80) NULL,
                `expires` TIMESTAMP NOT NULL,
                `scope` VARCHAR(80) NULL,
            PRIMARY KEY (`id`),
            INDEX `client_idx` (`client_id` ASC),
            INDEX `user_idx` (`user_id` ASC),
            CONSTRAINT `atokens_client`
                FOREIGN KEY (`client_id`)
                REFERENCES `oauth_clients` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `atokens_user`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS access_tokens');
    }
}
