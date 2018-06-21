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
 * AuthorizationCodes
 *
 * @ORM\Table(name="authorization_codes", indexes={@ORM\Index(name="client_idx", columns={"client_id"}), @ORM\Index(name="user_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Repositories\AuthorizationCode")
 */
class AuthorizationCodes
{
    use UUID;

    /**
     * @var string
     *
     * @ORM\Column(name="authorization_code", type="string", length=40, nullable=false)
     */
    private $authorizationCode;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", length=255, nullable=true)
     */
    private $redirectUri;

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
     * @var string
     *
     * @ORM\Column(name="id_token", type="string", length=255, nullable=true)
     */
    private $idToken;

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
     * Set authorizationCode
     *
     * @param string $authorizationCode
     *
     * @return AuthorizationCodes
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * Get authorizationCode
     *
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * Set redirectUri
     *
     * @param string $redirectUri
     *
     * @return AuthorizationCodes
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return AuthorizationCodes
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
     * @return AuthorizationCodes
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
     * Set idToken
     *
     * @param string $idToken
     *
     * @return AuthorizationCodes
     */
    public function setIdToken($idToken)
    {
        $this->idToken = $idToken;

        return $this;
    }

    /**
     * Get idToken
     *
     * @return string
     */
    public function getIdToken()
    {
        return $this->idToken;
    }

    /**
     * Set client
     *
     * @param \Entities\OauthClients $client
     *
     * @return AuthorizationCodes
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
     * @return AuthorizationCodes
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
     * @return mixed
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
            'code'      => $this->getAuthorizationCode(),
            'client_id' => $this->getClientId(),
            'user_id'   => $this->getUserId(),
            'expires'   => $this->getExpires()->getTimestamp(),
            'scope'     => $this->getScope(),
        ];
    }
}
