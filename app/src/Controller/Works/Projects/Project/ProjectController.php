<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works\Projects\Project;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller\Works\Projects\Project
 *
 * @Route("/work/project/{id}", requirements={"id"=UUIDv4::PATTERN})
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("", name="pm_work_project")
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('works/projects/project/show.html.twig', compact('project'));
    }
}