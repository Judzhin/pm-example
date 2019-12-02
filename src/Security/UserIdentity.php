<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserIdentity
 * @package App\Security
 */
class UserIdentity implements UserInterface, EquatableInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var array */
    private $roles;

    /** @var string */
    private $status;

    /**
     * UserIdentity constructor.
     * @param string $id
     * @param string $email
     * @param string $password
     * @param array $roles
     * @param string $status
     */
    public function __construct(string $id, string $email, string $password, array $roles, string $status)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === User::STATUS_DONE;
    }

    /**
     * @inheritdoc
     *
     * @param UserInterface|UserIdentity $user
     * @return bool|void
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id
            && $this->email === $user->email
            && $this->password === $user->password
            && $this->roles === $user->roles
            && $this->status === $user->status;
    }
}