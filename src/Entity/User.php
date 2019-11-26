<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity;

use App\Model\User\Email;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
     * @var Email
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var null
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmToken = null;

    /** @const STATUS_WAIT */
    private const STATUS_WAIT = 'WAIT';

    /** @const STATUS_DONE */
    private const STATUS_DONE = 'DONE';

    /**
     * @ORM\Column(type="string")
     */
    private $status = self::STATUS_WAIT;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="string")
     */
    private $createdAt;

    /**
     * User constructor.
     * @param UuidInterface|null $id
     * @throws \Exception
     */
    public function __construct(UuidInterface $id = null)
    {
        if ($id instanceof UuidInterface) {
            $this->id = $id;
        }

        $this->createdAt = new \DateTimeImmutable;
    }

    /**
     * @return null|UuidInterface
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return User
     */
    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @return null|string
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @param null $confirmToken
     * @return User
     */
    public function setConfirmToken($confirmToken = null): self
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return self::STATUS_WAIT === $this->status;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return self::STATUS_DONE === $this->status;
    }

    /**
     * @return User
     */
    public function confirmSignUp(): self
    {
        $this->status = self::STATUS_DONE;
        return $this;
    }
}
