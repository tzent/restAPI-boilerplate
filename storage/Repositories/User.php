<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\UserCredentialsInterface;

class User extends EntityRepository implements UserCredentialsInterface
{
    /**
     * @param string $email
     * @param string $password
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function checkUserCredentials($email, $password)
    {
        $user = $this->findOneByEmail($email);
        if ($user) {
            return $user->verifyPassword($password);
        }
        return false;
    }

    /**
     * @param string $email
     * @return array|bool|false
     * @throws \Doctrine\ORM\ORMException
     */
    public function getUserDetails($email)
    {
        $user = $this->findOneByEmail($email);
        if ($user) {
            return $user->toArray();
        }

        return false;
    }
}
