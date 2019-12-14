<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Entity;

use App\Model\Role;
use App\Model\User\Email;
use App\Model\User\Token;
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
 * @ORM\HasLifecycleCallbacks()
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
     * @var Email|null
     * @ORM\Column(type="email", length=180, unique=true, nullable=true)
     */
    private $email = null;

    /**
     * @var Name
     * @ORM\Embedded(class="App\Entity\Name")
     */
    private $name;

    /**
     * @var Email|null
     * @ORM\Column(type="email", length=180, unique=true, nullable=true)
     */
    private $newEmail = null;

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
     * @ORM\Embedded(class="EmbeddedToken", columnPrefix="new_confirm_token_")
     */
    private $newConfirmToken;

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
    public const STATUS_DONE = 'DONE';

    /**
     * @ORM\Column(type="string")
     */
    private $status = self::STATUS_NONE;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;

    /**
     * User constructor.
     * @param UuidInterface|null $id
     * @param Name|null $name
     * @throws \Exception
     */
    public function __construct(UuidInterface $id = null, Name $name = null)
    {
        if ($id) {
            $this->setId($id);
        }

        if ($name) {
            $this->setName($name);
        }

        $this->createdAt = new \DateTimeImmutable;
        $this->networks = new ArrayCollection;
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
     * @return Email|null
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @param Email|null $email
     * @return User
     */
    public function setEmail(Email $email = null): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param Name $name
     * @return User
     */
    public function setName(Name $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Email|null
     */
    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    /**
     * @param Email|null $newEmail
     * @return User
     */
    public function setNewEmail(Email $newEmail = null): User
    {
        $this->newEmail = $newEmail;
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
        // $roles[] = Role::user();
        $roles[] = Role::USER;
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
     * @return EmbeddedToken|null
     */
    public function getConfirmToken(): ?EmbeddedToken
    {
        return $this->confirmToken;
    }

    /**
     * @param EmbeddedToken|null $confirmToken
     * @return User
     */
    public function setConfirmToken(EmbeddedToken $confirmToken = null): self
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    /**
     * @return EmbeddedToken|null
     */
    public function getNewConfirmToken(): ?EmbeddedToken
    {
        return $this->newConfirmToken;
    }

    /**
     * @param EmbeddedToken|null $newConfirmToken
     * @return User
     */
    public function setNewConfirmToken(EmbeddedToken $newConfirmToken = null): User
    {
        $this->newConfirmToken = $newConfirmToken;
        return $this;
    }

    /**
     * @return EmbeddedToken|null
     */
    public function getResetToken(): ?EmbeddedToken
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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
     * @return ArrayCollection
     */
    public function getNetworks()
    {
        return $this->networks;
    }

    /**
     * @param $networks
     * @return User
     */
    public function setNetworks($networks): User
    {
        $this->networks = $networks;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUndefined(): bool
    {
        return self::STATUS_NONE === $this->status;
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
     * @param Name $name
     * @param Email $email
     * @param string $password
     * @param EmbeddedToken|null $confirmToken
     * @return User
     * @throws \Exception
     */
    public static function signUpByEmail(Name $name, Email $email, string $password, EmbeddedToken $confirmToken = null): self
    {
        /** @var UserInterface $user */
        $user = new self;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->confirmToken = $confirmToken ?? EmbeddedToken::create();
        $user->status = self::STATUS_WAIT;

        return $user;
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
        $this->confirmToken = null;
        return $this;
    }

    /**
     * @param Name $name
     * @param Network $network
     * @return User
     * @throws \Exception
     */
    public static function signUpByNetwork(Name $name, Network $network): self
    {
        /** @var self $user */
        $user = new self;
        $user->name = $name;
        $user->attachNetwork($network);
        $user->status = self::STATUS_DONE;

        return $user;
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
     * @throws \Exception
     */
    public function requestPasswordReset(EmbeddedToken $passwordResetToken): self
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        } else if (null === $this->email) {
            throw new \DomainException('Email is not specified.');
        } else if ($this->resetToken && !$this->resetToken->isExpiredTo(new \DateTimeImmutable)) {
            throw new \DomainException('Resetting is already requested.');
        }

        $this->resetToken = $passwordResetToken;
        return $this;
    }

    /**
     * @param Email $email
     * @param EmbeddedToken $newConfirmToken
     * @return User
     */
    public function requestEmailChanging(Email $email, EmbeddedToken $newConfirmToken): self
    {
        $this
            ->setNewEmail($email)
            ->setNewConfirmToken($newConfirmToken);

        return $this;
    }

    /**
     * @param Token $token
     * @return User
     */
    public function confirmEmailChanging(Token $token): self
    {
        if ($this->getNewConfirmToken()->getValue() !== $token->getValue()) {

        }

        $this
            ->setEmail($this->getNewEmail())
            ->setNewEmail(null);
        $this->setNewConfirmToken(null);

        return $this;
    }

    /**
     * @param \DateTimeImmutable $date
     * @param string $hash
     * @return User
     * @throws \Exception
     */
    public function passwordReset(\DateTimeImmutable $date, string $hash): self
    {

        if ($this->resetToken instanceof EmbeddedToken === false || $this->resetToken->isEmpty()) {
            throw new \DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->setPassword($hash);
        $this->resetToken = null;
        return $this;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbedes()
    {
        // if ($this->confirmToken->isEmpty()) {
        //     $this->confirmToken = null;
        // }

        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}
