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
use Entities\OauthClients;
use Entities\RefreshTokens as RefreshTokensEntity;
use Entities\Users;
use OAuth2\Storage\RefreshTokenInterface;


class RefreshToken extends EntityRepository implements RefreshTokenInterface
{
    /**
     * @param string $refresh_token
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getRefreshToken($refresh_token)
    {
        $refresh_token = $this->findOneByRefreshToken($refresh_token);
        if ($refresh_token) {
            return $refresh_token->toArray();
        }

        return false;
    }

    /**
     * @param string $refresh_token
     * @param string $client_id
     * @param string $user_id
     * @param int $expires
     * @param null $scope
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        $refresh_token = RefreshTokensEntity::fromArray([
            'refresh_token'  => $refresh_token,
            'client'         => $this->_em->getRepository(OauthClients::class)->findOneById($client_id),
            'user'           => $this->_em->getRepository(Users::class)->findOneById($user_id),
            'expires'        => (new \DateTime())->setTimestamp($expires),
            'scope'          => $scope,
        ]);

        $this->_em->persist($refresh_token);
        $this->_em->flush();
    }

    /**
     * @param string $refresh_token
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unsetRefreshToken($refresh_token)
    {
        $this->_em->remove($this->findOneByRefreshToken($refresh_token));
        $this->_em->flush();
    }
}
