<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works;

use App\Repository\ProjectRepository;
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

    /** @var ProjectRepository */
    private $projects;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ProjectsController constructor.
     *
     * @param ProjectRepository $projects
     * @param LoggerInterface $logger
     */
    public function __construct(ProjectRepository $projects, LoggerInterface $logger)
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
        $command->sort = $this->projects->findMaxSort() + 1;

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

//    /**
//     * @Route("/work/project/edit/{id}", name="pm_work_member_edit")
//     *
//     * @param Entity\Work\Member $member
//     * @param Request $request
//     * @param Project\Edit\Handler $handler
//     * @return Response
//     */
//    public function edit(Entity\Work\Member $member, Request $request, Project\Edit\Handler $handler): Response
//    {
////        /** @var Project\Edit\Command $command */
////        $command = Project\Edit\Command::parse($member);
////
////        /** @var FormInterface $form */
////        $form = $this->createForm(Project\Edit\FormType::class, $command);
////        $form->handleRequest($request);
////
////        if ($form->isSubmitted() && $form->isValid()) {
////            try {
////                $handler->handle($command);
////                return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
////            } catch (DomainException $exception) {
////                $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
////                $this->addFlash('error', $message);
////            }
////        }
////
////        return $this->render('works/members/edit.html.twig', [
////            'member' => $member,
////            'form' => $form->createView(),
////        ]);
//    }
//
//    /**
//     * @Route("/work/project/archive/{id}", name="pm_work_project_archive", methods={"POST"})
//     *
//     * @param Entity\Work\Member $member
//     * @param Request $request
//     * @param Project\Archived\Handler $handler
//     * @return Response
//     * @throws \Throwable
//     */
//    public function archive(Entity\Work\Member $member, Request $request, Project\Archived\Handler $handler): Response
//    {
//        if ($this->isCsrfTokenValid('archive', $request->request->get('token'))) {
//            /** @var Project\Archived\Command $command */
//            $command = new Project\Archived\Command($member->getId());
//
//            try {
//                $handler->handle($command);
//            } catch (DomainException $exception) {
//                $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
//                $this->addFlash('error', $message);
//            }
//        }
//
//        return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
//    }
//
////    /**
////     * @Route("/work/project/reinstate/{id}", name="pm_work_member_reinstate", methods={"POST"})
////     *
////     * @param Entity\Work\Member $member
////     * @param Request $request
////     * @param Project\Reinstate\Handler $handler
////     * @return Response
////     * @throws \Throwable
////     */
////    public function reinstate(Entity\Work\Member $member, Request $request, Project\Reinstate\Handler $handler): Response
////    {
////        if ($this->isCsrfTokenValid('reinstate', $request->request->get('token'))) {
////
////            if ($member->getId()->toString() === $this->getUser()->getId()) {
////                $this->addFlash('error', 'Unable to reinstate yourself.');
////            } else {
////                /** @var Project\Reinstate\Command $command */
////                $command = new Project\Reinstate\Command($member->getId());
////
////                try {
////                    $handler->handle($command);
////                } catch (DomainException $exception) {
////                    $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
////                    $this->addFlash('error', $message);
////                }
////            }
////
////        }
////
////        return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
////    }
//
//    /**
//     * @Route("{id}", name="pm_work_project_delete", methods={"DELETE"})
//     * @return Response
//     */
//    public function delete(): Response
//    {
//        return $this->redirectToRoute('pm_work_member', ['id' => 1]);
//    }
}
