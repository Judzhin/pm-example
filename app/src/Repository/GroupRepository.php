<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Repository;

use App\Entity\Work\Member\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class GroupRepository
 * @package App\Repository
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface */
    private $paginator;

    /**
     * GroupRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Group::class);
        $this->paginator = $paginator;
    }

    /**
     * @param Group $group
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Group $group): self
    {
        $this->_em->persist($group);
        $this->_em->flush();
        return $this;
    }

    /**
     * @return Group[]
     */
    public function all()
    {
        return $this->findAll();
    }

    /**
     * @return array
     */
    public function assoc(): array
    {
        /** @var  $qb */
        $qb = $this->createQueryBuilder('g');
        return array_column($qb->getQuery()->getArrayResult(), 'name', 'id');
    }

}
