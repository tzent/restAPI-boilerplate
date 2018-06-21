<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace Entities;

use App\Base\Traits\Encryptable;
use App\Base\Traits\UUID;
use Doctrine\ORM\Mapping as ORM;

/**
 * OauthClients
 *
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity(repositoryClass="Repositories\OauthClient")
 */
class OauthClients
{
    use UUID, Encryptable;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=80, nullable=false)
     */
    private $clientSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=false)
     */
    private $isPublic = 0;

    /**
     * Set clientSecret
     *
     * @param string $clientSecret
     *
     * @return OauthClients
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $this->encryptField($clientSecret);

        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Verify client's secret
     *
     * @param string $clientSecret
     *
     * @return bool
     */
    public function verifyClientSecret($clientSecret)
    {
        return $this->verifyEncryptedFieldValue($this->getClientSecret(), $clientSecret);
    }

    /**
     * Set public status
     *
     * @param string $isPublic
     *
     * @return OauthClients
     */
    public function setIPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get public status
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return !empty($this->isPublic);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'client_id'         => $this->getId(),
            'client_identifier' => $this->getClientIdentifier(),
            'client_secret'     => $this->getClientSecret(),
            'is_public'         => $this->getIsPublic()
        ];
    }
}
