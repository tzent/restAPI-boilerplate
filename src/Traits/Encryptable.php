<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Traits;


trait Encryptable
{
    /**
     * @var array
     */
    protected $hashOptions = ['cost' => 11];

    /**
     * @param string $value
     * @return bool|string
     */
    protected function encryptField($value)
    {
        return password_hash($value, PASSWORD_BCRYPT, $this->hashOptions);
    }

    /**
     * @param string $hash
     * @param string $value
     * @return bool
     */
    protected function verifyEncryptedFieldValue($hash, $value)
    {
        return password_verify($value, $hash);
    }
}