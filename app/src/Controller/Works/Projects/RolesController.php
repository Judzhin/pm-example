<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works\Projects;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
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
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('works/projects/roles/index.html.twig', []);
    }
}