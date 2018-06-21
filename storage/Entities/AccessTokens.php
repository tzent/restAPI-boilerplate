<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace Entities;

use App\Base\Helpers\Hydrator;
use App\Base\Traits\UUID;
use Doctrine\ORM\Mapping as ORM;

/**
 * AccessTokens
 *
 * @ORM\Table(name="access_tokens", indexes={@ORM\Index(name="client_idx", columns={"client_id"}), @ORM\Index(name="user_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Repositories\AccessToken")
 */
class AccessTokens
{
    use UUID;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=40, nullable=false)
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime", nullable=false)
     */
    private $expires;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=80, nullable=true)
     */
    private $scope;

    /**
     * @var \Entities\OauthClients
     *
     * @ORM\ManyToOne(targetEntity="Entities\OauthClients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Set accessToken
     *
     * @param string $accessToken
     *
     * @return AccessTokens
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return AccessTokens
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return AccessTokens
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set client
     *
     * @param \Entities\OauthClients $client
     *
     * @return AccessTokens
     */
    public function setClient(\Entities\OauthClients $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Entities\OauthClients
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get client id
     *
     * @return null|string
     */
    public function getClientId()
    {
        return $this->client instanceof OauthClients ? $this->client->getId() : null;
    }

    /**
     * Set user
     *
     * @param \Entities\Users $user
     *
     * @return AccessTokens
     */
    public function setUser(\Entities\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Entities\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getUserId()
    {
        return $this->user instanceof Users ? $this->user->getId() : null;
    }

    /**
     * @param array $params
     * @return AccessTokens
     */
    public static function fromArray(array $params)
    {
        return Hydrator::fromArray(new self(), $params);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'token'     => $this->getAccessToken(),
            'client_id' => $this->getClientId(),
            'user_id'   => $this->getUserId(),
            'expires'   => $this->getExpires()->getTimestamp(),
            'scope'     => $this->getScope(),
        ];
    }
}
