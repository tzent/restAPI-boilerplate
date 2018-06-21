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
use App\Base\Traits\Encryptable;
use App\Base\Traits\UUID;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="emailx", columns={"email"})})
 * @ORM\Entity(repositoryClass="Repositories\User")
 */
class Users
{
    use UUID, Encryptable;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="user.first_name.not_blank",
     *     groups={"signup"}
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "user.first_name.min_length",
     *     maxMessage = "user.first_name.max_length",
     *     groups={"signup"}
     * )
     *
     * @ORM\Column(name="first_name", type="string", length=20, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="user.last_name.not_blank",
     *     groups={"signup"}
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "user.last_name.min_length",
     *     maxMessage = "user.last_name.max_length",
     *     groups={"signup"}
     * )
     *
     * @ORM\Column(name="last_name", type="string", length=20, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="user.email.not_blank",
     *     groups={"signin", "signup"}
     * )
     * @Assert\Email(
     *     message="user.email.invalid_format",
     *     checkMX=true,
     *     groups={"signup"}
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "user.email.min_length",
     *     maxMessage = "user.email.max_length",
     *     groups={"signin", "signup"}
     * )
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="user.password.not_blank",
     *     groups={"signup"}
     * )
     * @Assert\Length(
     *     min = 8,
     *     max = 16,
     *     minMessage = "user.password.min_length",
     *     maxMessage = "user.password.max_length",
     *     groups={"signup"}
     * )
     *
     * @ORM\Column(name="password", type="string", length=80, nullable=false)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_verified", type="boolean", nullable=true)
     */
    private $emailVerified;

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Users
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Users
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Encrypt password
     */
    public function encryptPassword()
    {
        $this->password = $this->encryptField($this->password);
    }

    /**
     * Verify user's password
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return $this->verifyEncryptedFieldValue($this->getPassword(), $password);
    }

    /**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     *
     * @return Users
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    /**
     * Get emailVerified
     *
     * @return boolean
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
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
            'user_id'        => $this->getId(),
            'first_name'     => $this->getFirstName(),
            'last_name'      => $this->getLastName(),
            'email'          => $this->getEmail(),
            'email_verified' => $this->getEmailVerified(),
            'scope'          => null,
        ];
    }
}
