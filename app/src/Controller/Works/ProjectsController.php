<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works;

use App\ReadModel\Work\ProjectFetcher;
use App\UseCase\Work\Project;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectsController
 * @package App\Controller\Work
 */
class ProjectsController extends AbstractController
{
    /** @const PER_PAGE */
    private const PER_PAGE = 10;

    /** @var ProjectFetcher */
    private $projects;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ProjectsController constructor.
     *
     * @param ProjectFetcher $projects
     * @param LoggerInterface $logger
     */
    public function __construct(ProjectFetcher $projects, LoggerInterface $logger)
    {
        $this->projects = $projects;
        $this->logger = $logger;
    }

    /**
     * @Route("/work/projects", name="pm_work_projects")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var Project\Filter\Filter $filter */
        $filter = new Project\Filter\Filter;

        /** @var FormInterface|Project\Create\FormType $form */
        $form = $this->createForm(Project\Filter\FormType::class, $filter);
        $form->handleRequest($request);

        /** @var PaginationInterface $pagination */
        $pagination = $this->projects->all(
            $filter, $request->query->getInt('page', 1), self::PER_PAGE
        );

        return $this->render('works/projects/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/work/project/create", name="pm_work_project_create")
     *
     * @param Request $request
     * @param Project\Create\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function create(Request $request, Project\Create\Handler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_WORK_MANAGE_PROJECTS');

        /** @var Project\Create\Command $command */
        $command = new Project\Create\Command;
        $command->sort = $this->projects->maxSort() + 1;

        /** @var FormInterface|Project\Create\FormType $form */
        $form = $this->createForm(Project\Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            return $this->redirectToRoute('pm_work_projects');
        }

        return $this->render('works/projects/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
