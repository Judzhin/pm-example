<?php


namespace App\ReadModel\Work\Project;

use App\Repository\Work\Project\RoleRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RoleFetcher
 * @package App\ReadModel\Work\Project
 */
class RoleFetcher
{
    /** @var RoleRepository */
    private $roles;

    /** @var PaginatorInterface */
    private $paginator;

    /**
     * RoleFetcher constructor.
     * @param RoleRepository $roles
     * @param PaginatorInterface $paginator
     */
    public function __construct(RoleRepository $roles, PaginatorInterface $paginator)
    {
        $this->roles = $roles;
        $this->paginator = $paginator;
    }

    public function all()
    {

    }
}