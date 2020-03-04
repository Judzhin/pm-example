<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Repository;

use App\Entity\Work\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM;
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
     *
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Project::class);
        $this->paginator = $paginator;
    }

    /**
     * @param Project $project
     * @return $this
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     */
    public function add(Project $project): self
    {
        $this->_em->persist($project);
        $this->_em->flush();
        return $this;
    }
}