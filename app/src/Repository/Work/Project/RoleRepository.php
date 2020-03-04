<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Repository\Work\Project;

use App\Entity\Work\Member\Group;
use App\Entity\Work\Project\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RoleRepository
 * @package App\Repository\Work\Project
 *
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * RoleRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Role::class);
        $this->paginator = $paginator;
    }
}
