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
use OAuth2\Storage\AuthorizationCodeInterface;

class AuthorizationCode extends EntityRepository implements AuthorizationCodeInterface
{
    /**
     * @param string $auth_code
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function getAuthorizationCode($auth_code)
    {
        $auth_code = $this->findOneByAuthorizationCode($auth_code);
        if ($auth_code) {
            return $auth_code->toArray();
        }

        return false;
    }

    /**
     * @param string $code
     * @param string $client_id
     * @param string $user_id
     * @param string $redirect_uri
     * @param int $expires
     * @param null $scope
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        $authCode = OAuthAuthorizationCode::fromArray([
            'code'           => $code,
            'client'         => $this->_em->getRepository(OauthClient::class)->findOneById($client_id),
            'user'           => $this->_em->getRepository(User::class)->findOneById($user_id),
            'redirect_uri'   => $redirect_uri,
            'expires'        => (new \DateTime())->setTimestamp($expires),
            'scope'          => $scope,
        ]);
        $this->_em->persist($authCode);
        $this->_em->flush();
    }

    /**
     * @param string $auth_code
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function expireAuthorizationCode($auth_code)
    {
        $this->_em->remove($this->findOneByAuthorizationCode($auth_code));
        $this->_em->flush();
    }
}
