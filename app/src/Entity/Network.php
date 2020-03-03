<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Self_;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Network
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user_networks")
 */
class Network
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue("UUID")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="networks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=180)
     */
    private $network;

    /**
     * @var string
     * @ORM\Column(type="string", length=180)
     */
    private $identity;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     * @return Network
     */
    public function setId(UuidInterface $id): Network
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Network
     */
    public function setUser(User $user): Network
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @param string $network
     * @return Network
     */
    public function setNetwork(string $network): Network
    {
        $this->network = $network;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return Network
     */
    public function setIdentity(string $identity): Network
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @param self $network
     * @return bool
     */
    public function isExists(self $network): bool
    {
        return $this->network === $network->getNetwork();
    }

    /**
     * @param string $network
     * @param string $identity
     * @return Network
     */
    public static function factory(string $network, string $identity): self
    {
        /** @var self $entity */
        $entity = new self;
        $entity->network = $network;
        $entity->identity = $identity;

        return $entity;
    }
}