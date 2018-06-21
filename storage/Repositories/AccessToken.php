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
use Entities\AccessTokens as AccessTokensEntity;
use Entities\AccessTokens;
use Entities\OauthClients;
use Entities\RefreshTokens;
use Entities\Users;
use OAuth2\Storage\AccessTokenInterface;

class AccessToken extends EntityRepository implements AccessTokenInterface
{
    /**
     * @param string $oauth_token
     * @return array|bool|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function getAccessToken($oauth_token)
    {
        $token = $this->findOneByAccessToken($oauth_token);
        if ($token) {
            return $token->toArray();
        }

        return false;
    }

    /**
     * @param string $oauth_token
     * @param sting $client_id
     * @param string $user_id
     * @param int $expires
     * @param null $scope
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        $client = $this->_em->getRepository(OauthClients::class)->findOneById($client_id);
        $user   = $this->_em->getRepository(Users::class)->findOneById($user_id);

        $this->removeExpired($client, $user);

        $token = AccessTokensEntity::fromArray([
            'access_token' => $oauth_token,
            'client'       => $client,
            'user'         => $user,
            'expires'      => (new \DateTime())->setTimestamp($expires),
            'scope'        => $scope,
        ]);
        $this->_em->persist($token);
        $this->_em->flush();
    }

    /**
     * @param OauthClients $client
     * @param Users $user
     */
    private function removeExpired(OauthClients $client, Users $user)
    {
        $qb         = $this->createQueryBuilder('t');
        $old_tokens = $qb->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('t.client', '?0'),
                    $qb->expr()->eq('t.user', '?1'),
                    $qb->expr()->gte('t.expires', '?2')
                )
            )
            ->setParameters([
                $client,
                $user,
                (new \DateTime())->format('Y-m-d H:i:s')
            ])
            ->getQuery()
            ->getResult();

        if (count($old_tokens)) {
            foreach ($old_tokens as $token) {
                $this->_em->remove($token);
            }
        }
    }
}
