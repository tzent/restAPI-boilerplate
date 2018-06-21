<?php

namespace Entities;

use App\Base\Traits\UUID;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jwts
 *
 * @ORM\Table(name="jwts", indexes={@ORM\Index(name="client_idx", columns={"client_id"})})
 * @ORM\Entity
 */
class Jwts
{
    use UUID;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=80, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="public_key", type="string", nullable=false)
     */
    private $publicKey;

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
     * Set subject
     *
     * @param string $subject
     *
     * @return Jwts
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set publicKey
     *
     * @param string $publicKey
     *
     * @return Jwts
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set client
     *
     * @param \Entities\OauthClients $client
     *
     * @return Jwts
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
}
