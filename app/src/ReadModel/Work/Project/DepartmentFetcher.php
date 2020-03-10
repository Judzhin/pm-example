<?php


namespace App\ReadModel\Work\Project;

use App\Repository\Work\Project\RoleRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class DepartmentFetcher
 *
 * @package App\ReadModel\Work\Project
 */
class DepartmentFetcher
{
    /** @var RoleRepository */
    private $roles;

    /**
     * RoleFetcher constructor.
     *
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->roles->findAll();
    }
}