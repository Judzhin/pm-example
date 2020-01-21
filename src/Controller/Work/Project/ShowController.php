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
 * Class ShowController
 * @package App\Controller\Work\Project
 */
class ShowController extends AbstractController
{
    /**
     * @Route("/work/project/{id}", name="pm_work_project", requirements={"id"=UUIDv4::PATTERN})
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('works/members/show.html.twig', compact('project'));
    }
}