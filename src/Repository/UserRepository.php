<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Repository;

use App\Entity\EmbeddedToken;
use App\Entity\Network;
use App\Entity\User;
use App\Model\User\Email;
use App\UseCase\User\Filter\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository
 * @package App\Repository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
        $this->paginator = $paginator;
    }

    /**
     * @inheritdoc
     *
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param Network $network
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByNetwork(Network $network): ?User
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('u');

        $qb
            ->join('u.networks', 'n', Join::WITH, 'u.id = n.user')
            ->where($qb->expr()->eq('n.network', ':network'))
            ->andWhere($qb->expr()->eq('n.identity', ':identity'))
            ->setParameter('network', $network->getNetwork())
            ->setParameter('identity', $network->getIdentity());

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Email $email
     * @return User|null
     */
    public function findOneByEmail(Email $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    // /**
    //  * @param EmbeddedToken $confirmToken
    //  * @return User|null
    //  * @deprecated
    //  */
    // public function findOneByConfirmToken(EmbeddedToken $confirmToken): ?User
    // {
    //     return $this->findOneBy(['confirmToken.value' => $confirmToken->getValue()]);
    // }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $limit
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $limit): PaginationInterface
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('u');

        if ($filter->name) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(CONCAT(u.name.first, \' \', u.name.last))', ':name')
            )->setParameter('name', '%'.mb_strtolower($filter->name).'%');
        }

        if ($filter->email) {
            $qb->andWhere(
                $qb->expr()->like('u.email', ':email')
            )->setParameter('email', '%'.mb_strtolower($filter->email).'%');
        }

        if ($filter->status) {
            $qb->andWhere(
                $qb->expr()->in('u.status', ':status')
            )->setParameter('status', $filter->status);
        }

        if ($filter->roles) {
            //$qb->andWhere(
            //
            //)->setParameter('status', $filter->status);
        }

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'u.createdAt', 'u.name.first', 'u.email', 'u.status'
            ],
            PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'u.createdAt',
            PaginatorInterface::DEFAULT_SORT_DIRECTION => 'desc',
        ]);
    }
}
