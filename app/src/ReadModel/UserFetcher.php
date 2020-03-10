<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\ReadModel;

use App\Repository\UserRepository;
use App\UseCase\User\Filter\Filter;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserFetcher
 * @package App\ReadModel
 */
class UserFetcher
{
    /** @var UserRepository */
    private $users;

    /** @var PaginatorInterface */
    private $paginator;

    /**
     * UserFetcher constructor.
     *
     * @param UserRepository $users
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $users, PaginatorInterface $paginator)
    {
        $this->users = $users;
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
        $qb = $this->users->createQueryBuilder('u');

        if ($filter->name) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(CONCAT(u.name.first, \' \', u.name.last))', ':name')
            )->setParameter('name', '%' . mb_strtolower($filter->name) . '%');
        }

        if ($filter->email) {
            $qb->andWhere(
                $qb->expr()->like('u.email', ':email')
            )->setParameter('email', '%' . mb_strtolower($filter->email) . '%');
        }

        if ($filter->status) {
            $qb->andWhere(
                $qb->expr()->in('u.status', ':status')
            )->setParameter('status', $filter->status);
        }

        // if ($filter->roles) {
        //     //$qb->andWhere(
        //     //
        //     //)->setParameter('status', $filter->status);
        // }

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'u.createdAt', 'u.name.first', 'u.email', 'u.status'
            ],
            PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'u.createdAt',
            PaginatorInterface::DEFAULT_SORT_DIRECTION => 'desc',
        ]);
    }
}