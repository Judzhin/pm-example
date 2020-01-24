<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security;

use App\Entity\Network;
use App\Entity\User;
use App\Model\Email;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /** @var GroupRepository */
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
     * @throws NonUniqueResultException
     * @throws \Throwable
     */
    private function findUserByIdentity(string $identity): User
    {
        /** @var array $chunks */
        $chunks = explode(':', $identity);

        if (2 === count($chunks)
            && $user = $this->repository->findOneByNetwork(Network::factory($chunks[0], $chunks[1]))){
            return $user;
        }

        /** @var User $user */
        if ($user = $this->repository->findOneByEmail(new Email($identity))) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('User "%s" not found.', $identity));
    }

    /**
     * @param User $user
     * @return UserInterface
     */
    private static function createIdentity(User $user): UserInterface
    {
        return new UserIdentity(
            $user->getId()->toString(),
            $user->getUsername(),
            $user->getPassword(),
            $user->getName()->getDisplay(),
            $user->getRoles(),
            $user->getStatus()
        );
    }

    /**
     * @param string $username
     * @return UserInterface
     * @throws NonUniqueResultException
     * @throws \Throwable
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        return self::createIdentity(
            $this->findUserByIdentity($username)
        );
    }

    /**
     * @param UserInterface $user
     * @return UserIdentity
     * @throws NonUniqueResultException
     * @throws \Throwable
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