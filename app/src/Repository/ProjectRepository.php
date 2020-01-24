<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Repository;


use App\Entity\Work\Project;
use App\UseCase\Work\Project\Filter\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProjectRepository
 * @package App\Repository
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * ProjectRepository constructor.
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Project::class);
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
        $qb = $this->createQueryBuilder('p');

        // if ($filter->name) {
        //     $qb->andWhere(
        //         $qb->expr()->like('LOWER(CONCAT(m.name.first, \' \', m.name.last))', ':name')
        //     )->setParameter('name', '%'.mb_strtolower($filter->name).'%');
        // }
        //
        // if ($filter->email) {
        //     $qb->andWhere(
        //         $qb->expr()->like('m.email', ':email')
        //     )->setParameter('email', '%'.mb_strtolower($filter->email).'%');
        // }
        //
        // if ($filter->status) {
        //     $qb->andWhere(
        //         $qb->expr()->in('m.status', ':status')
        //     )->setParameter('status', $filter->status);
        // }

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'p.name', 'm.status'
            ],
            // PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'u.createdAt',
            // PaginatorInterface::DEFAULT_SORT_DIRECTION => 'desc',
        ]);
    }
}
