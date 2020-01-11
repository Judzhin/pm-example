<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Repository;

use App\Entity\Network;
use App\Entity\User;
use App\Entity\Work\Member;
use App\Exception\UnsupportedUserException;
use App\Model\User\Email;

use App\UseCase\Work\Member\Filter\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MemberRepository
 * @package App\Repository
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * MemberRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Member::class);
        $this->paginator = $paginator;
    }

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

        //if ($filter->name) {
        //    $qb->andWhere(
        //        $qb->expr()->like('LOWER(CONCAT(u.name.first, \' \', u.name.last))', ':name')
        //    )->setParameter('name', '%'.mb_strtolower($filter->name).'%');
        //}
        //
        //if ($filter->email) {
        //    $qb->andWhere(
        //        $qb->expr()->like('u.email', ':email')
        //    )->setParameter('email', '%'.mb_strtolower($filter->email).'%');
        //}
        //
        //if ($filter->status) {
        //    $qb->andWhere(
        //        $qb->expr()->in('u.status', ':status')
        //    )->setParameter('status', $filter->status);
        //}
        //
        //if ($filter->roles) {
        //    //$qb->andWhere(
        //    //
        //    //)->setParameter('status', $filter->status);
        //}

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'u.name.first', 'u.email', 'u.status'
            ],
            // PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'u.createdAt',
            // PaginatorInterface::DEFAULT_SORT_DIRECTION => 'desc',
        ]);
    }
}
