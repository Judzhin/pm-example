<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity;

use App\Model\User\Email;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var EmbeddedToken
     * @ORM\Embedded(class="EmbeddedToken", columnPrefix="confirm_token_")
     */
    private $confirmToken;

    /**
     * @var EmbeddedToken
     * @ORM\Embedded(class="EmbeddedToken", columnPrefix="reset_token_")
     */
    private $resetToken;

    /** @const STATUS_NONE */
    private const STATUS_NONE = 'NONE';

    /** @const STATUS_WAIT */
    private const STATUS_WAIT = 'WAIT';

    /** @const STATUS_DONE */
    private const STATUS_DONE = 'DONE';

    /**
     * @ORM\Column(type="string")
     */
    private $status = self::STATUS_NONE;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="string")
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user")
     */
    private $networks;

    /**
     * User constructor.
     * @param UuidInterface|null $id
     * @throws \Exception
     */
    public function __construct(UuidInterface $id = null)
    {
        if (null !== $id) {
            $this->setId($id);
        }

        $this->createdAt = new \DateTimeImmutable;
        $this->networks = new ArrayCollection;
    }

    /**
     * @param Email $email
     * @param string $password
     * @param string $token
     * @return User
     */
    public function signUpByEmail(Email $email, string $password, string $token): self
    {
        if (!$this->isUndefined()) {
            throw new \DomainException('User is already signed up.');
        }

        $this
            ->setEmail($email)
            ->setPassword($password)
            ->setConfirmToken(EmbeddedToken::create());

        $this->status = self::STATUS_WAIT;

        return $this->setEmail($email);
    }

    /**
     * @return User
     */
    public function confirmSignUp(): self
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_DONE;
        return $this;
    }

    /**
     * @param Network $network
     * @return User
     */
    public function signUpByNetwork(Network $network): self
    {
        if (!$this->isUndefined()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->attachNetwork($network);
        $this->status = self::STATUS_DONE;
    }

    /**
     * @param Network $network
     * @return User
     */
    private function attachNetwork(Network $network): self
    {
        /** @var Network $self */
        foreach ($this->networks as $self) {
            if ($self->isExists($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $this->networks->add($network->setUser($this));
        return $this;
    }

    /**
     * @param EmbeddedToken $passwordResetToken
     * @return User
     */
    public function requestPasswordReset(EmbeddedToken $passwordResetToken): self
    {
        $this->resetToken = $passwordResetToken;
        return $this;
    }

    /**
     * @param \DateTimeImmutable $date
     * @param $hash
     * @return User
     */
    public function passwordReset(\DateTimeImmutable $date, $hash): self
    {

        if ($this->resetToken->isEmpty()) {
            throw new \DomainException('Resetting is not requested');
        }

        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->setPassword($hash);
        $this->resetToken = null;
        return $this;
    }

    /**
     * @return null|UuidInterface
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     * @return User
     */
    public function setId(UuidInterface $id): self
    {
        $this->id = $id;
        return $this;
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

    /**
     * @param array $roles
     * @return User
     */
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
     * @return EmbeddedToken
     */
    public function getResetToken(): EmbeddedToken
    {
        return $this->resetToken;
    }

    /**
     * @param EmbeddedToken $resetToken
     * @return User
     */
    public function setResetToken(EmbeddedToken $resetToken): User
    {
        $this->resetToken = $resetToken;
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
    public function isUndefined(): bool
    {
        return self::STATUS_DONE === $this->status;
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
}
