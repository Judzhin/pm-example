<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\ReadModel\Work;

use App\Entity\Work\Project;
use App\Repository\ProjectRepository;
use App\UseCase\Work\Project\Filter\Filter;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProjectFetcher
 * @package App\ReadModel\Work
 */
class ProjectFetcher
{
    /** @var ProjectRepository */
    private $projects;

    /** @var PaginatorInterface */
    private $paginator;

    /**
     * ProjectFetcher constructor.
     *
     * @param ProjectRepository $projects
     * @param PaginatorInterface $paginator
     */
    public function __construct(ProjectRepository $projects, PaginatorInterface $paginator)
    {
        $this->projects = $projects;
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
        $qb = $this->projects->createQueryBuilder('p');

        if ($filter->name) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(p.name)', ':name')
            )->setParameter('name', '%'.mb_strtolower($filter->name).'%');
        }

        if ($filter->status) {
            $qb->andWhere(
                $qb->expr()->in('p.status', ':status')
            )->setParameter('status', $filter->status);
        }

        return $this->paginator->paginate($qb, $page, $limit, [
            PaginatorInterface::SORT_FIELD_WHITELIST => [
                'p.name', 'p.status'
            ],
        ]);
    }

    /**
     * @return int
     */
    public function maxSort(): int
    {
        /** @var Project $entity */
        if ($entity = $this->projects->findOneBy([], ['sort' => 'DESC'])) {
            return $entity->getSort();
        }

        return 0;
    }
}