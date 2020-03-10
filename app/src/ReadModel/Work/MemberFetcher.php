<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\ReadModel\Work;

use App\Repository\MemberRepository;
use App\UseCase\Work\Member\Filter\Filter;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class MemberFetcher
 *
 * @package App\ReadModel\Work
 */
class MemberFetcher
{
    /** @var MemberRepository */
    private $members;

    /** @var PaginatorInterface */
    private $paginator;

    /**
     * MemberFetcher constructor.
     *
     * @param MemberRepository $members
     * @param PaginatorInterface $paginator
     */
    public function __construct(MemberRepository $members, PaginatorInterface $paginator)
    {
        $this->members = $members;
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
        $qb = $this->members->createQueryBuilder('m');

        if ($filter->name) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(CONCAT(m.name.first, \' \', m.name.last))', ':name')
            )->setParameter('name', '%'.mb_strtolower($filter->name).'%');
        }

        if ($filter->email) {
            $qb->andWhere(
                $qb->expr()->like('m.email', ':email')
            )->setParameter('email', '%'.mb_strtolower($filter->email).'%');
        }

        if ($filter->status) {
            $qb->andWhere(
                $qb->expr()->in('m.status', ':status')
            )->setParameter('status', $filter->status);
        }

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'm.name.first', 'm.name.last', 'm.email', 'm.status'
            ],
            // PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'u.createdAt',
            // PaginatorInterface::DEFAULT_SORT_DIRECTION => 'desc',
        ]);
    }

}