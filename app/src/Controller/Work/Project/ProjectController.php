<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Work\Project;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller\Work\Project
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
        return $this->render('works/projects/show.html.twig', compact('project'));
    }

    /**
     * @Route("/setting", name="pm_work_project_setting")
     *
     * @param Project $project
     * @return Response
     */
    public function setting(Project $project): Response
    {
        return $this->render('works/projects/setting.html.twig', compact('project'));
    }
}