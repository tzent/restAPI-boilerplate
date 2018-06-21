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
use OAuth2\Storage\ClientCredentialsInterface;

class OauthClient extends EntityRepository implements ClientCredentialsInterface
{
    /**
     * @param string $client_id
     * @return array|bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function getClientDetails($client_id)
    {
        $client = $this->findOneById($client_id);
        if ($client) {
            return $client->toArray();
        }

        return false;
    }

    /**
     * @param string $client_id
     * @param null|string $client_secret
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $client = $this->findOneById($client_id);
        if ($client) {
            return $client->verifyClientSecret($client_secret);
        }

        return false;
    }

    /**
     * @param string $client_id
     * @param string $grant_type
     * @return bool
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        return true;
    }

    /**
     * @param $client_id
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function isPublicClient($client_id)
    {
        $client = $this->findOneById($client_id);
        if ($client) {
            return $client->isPublic();
        }

        return false;
    }

    /**
     * @param string $client_id
     * @return null
     */
    public function getClientScope($client_id)
    {
        return null;
    }
}
