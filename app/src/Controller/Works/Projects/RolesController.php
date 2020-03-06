<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works\Projects;

use App\Model\Work\Project\Permission;
use App\ReadModel\Work\Project\RoleFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoleController
 * @package App\Controller\Work\Project
 *
 * @Route("/work/projects/roles")
 */
class RolesController extends AbstractController
{
    /**
     * @Route("", name="pm_work_projects_roles")
     *
     * @param RoleFetcher $roleFetcher
     * @return Response
     */
    public function index(RoleFetcher $roleFetcher): Response
    {
        return $this->render('works/projects/roles/index.html.twig', [
            'roles' => $roleFetcher->all(),
            'permissions' => Permission::values()
        ]);
    }
}