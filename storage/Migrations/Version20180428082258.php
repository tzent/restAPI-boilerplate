<?php

namespace Storage\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180428082258 extends AbstractMigration
{
    /**
     * Get migration description
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create table users if not exists';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS `users` (
                `id` VARCHAR(36) NOT NULL,
                `first_name` VARCHAR(20) NOT NULL,
                `last_name` VARCHAR(20) NOT NULL,
                `email` VARCHAR(30) NULL,
                `password` VARCHAR(80) NOT NULL,
                `email_verified` TINYINT(1) NULL,
            PRIMARY KEY (`id`),
            INDEX `emailx` (`email` ASC))
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS users');
    }
}
