<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Ramsey\Uuid\Exception\UnsupportedOperationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package App\Security
 */
class UserProvider implements UserProviderInterface
{

    /** @var UserRepository */
    protected $repository;

    /**
     * UserProvider constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $identity
     * @return User
     */
    private function findUserByIdentity(string $identity): User
    {
        /** @var User $user */
        if (!$user = $this->repository->findOneByEmail($identity)) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $identity));
        }

        return $user;
    }

    /**
     * @param User $user
     * @return UserInterface
     */
    private static function createIdentity(User $user): UserInterface
    {
        return new UserIdentity(
            $user->getId()->toString(),
            $user->getEmail()->getValue(),
            $user->getPassword(),
            $user->getRoles(),
            $user->getStatus()
        );
    }

    /**
     * @inheritdoc
     *
     * @param string $username
     * @return UserInterface
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        return self::createIdentity(
            $this->findUserByIdentity($username)
        );
    }

    /**
     * @inheritdoc
     *
     * @param UserInterface $user
     * @return UserIdentity
     */
    public function refreshUser(UserInterface $user): UserIdentity
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedOperationException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return self::createIdentity(
            $this->findUserByIdentity(
                $user->getUsername()
            )
        );
    }

    /**
     * @inheritdoc
     *
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return $class instanceof UserIdentity;
    }
}