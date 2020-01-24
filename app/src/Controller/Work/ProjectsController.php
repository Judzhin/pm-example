<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Work;

use App\Entity;
use App\Exception\DomainException;
use App\Repository\ProjectRepository;
use App\UseCase\Work\Project;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ProjectsController constructor.
     * @param ProjectRepository $repository
     * @param LoggerInterface $logger
     */
    public function __construct(ProjectRepository $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
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
        $form = $this->createForm(Project\Filter\FormType::class, $filter);
        $form->handleRequest($request);

        /** @var PaginationInterface $pagination */
        $pagination = $this->repository->all(
            $filter, $request->query->getInt('page', 1), self::PER_PAGE
        );

        return $this->render(
            'works/projects/index.html.twig',
            [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/work/project/create/{user_id}", name="pm_work_member_create")
     * @ParamConverter("user", options={"id" = "user_id"}, class="App\Entity\User")
     *
     * @param Entity\User $user
     * @param Request $request
     * @param Project\Create\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function create(Entity\User $user, Request $request, Project\Create\Handler $handler): Response
    {
        /** @var  $parameters */
        $parameters = ['id' => $user->getId()];

        if ($this->repository->find($user->getId())) {
            $this->addFlash('error', 'Member already exists.');
            return $this->redirectToRoute('pm_user', $parameters);
        }

        /** @var Project\Create\Command $command */
        $command = new Project\Create\Command($user->getId());
        $command->firstName = $user->getName()->getFirst();
        $command->lastName = $user->getName()->getLast();
        $command->email = $user->getEmail();

        /** @var FormInterface $form */
        $form = $this->createForm(Project\Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('pm_work_member', $parameters);
            } catch (DomainException $exception) {
                $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('works/members/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/work/project/edit/{id}", name="pm_work_member_edit")
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Project\Edit\Handler $handler
     * @return Response
     */
    public function edit(Entity\Work\Member $member, Request $request, Project\Edit\Handler $handler): Response
    {
        /** @var Project\Edit\Command $command */
        $command = Project\Edit\Command::parse($member);

        /** @var FormInterface $form */
        $form = $this->createForm(Project\Edit\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
            } catch (DomainException $exception) {
                $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('works/members/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/work/project/archive/{id}", name="pm_work_member_archive", methods={"POST"})
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Project\Archived\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function archive(Entity\Work\Member $member, Request $request, Project\Archived\Handler $handler): Response
    {
        if ($this->isCsrfTokenValid('archive', $request->request->get('token'))) {
            /** @var Project\Archived\Command $command */
            $command = new Project\Archived\Command($member->getId());

            try {
                $handler->handle($command);
            } catch (DomainException $exception) {
                $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
    }

    /**
     * @Route("/work/project/reinstate/{id}", name="pm_work_member_reinstate", methods={"POST"})
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Project\Reinstate\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function reinstate(Entity\Work\Member $member, Request $request, Project\Reinstate\Handler $handler): Response
    {
        if ($this->isCsrfTokenValid('reinstate', $request->request->get('token'))) {

            if ($member->getId()->toString() === $this->getUser()->getId()) {
                $this->addFlash('error', 'Unable to reinstate yourself.');
            } else {
                /** @var Project\Reinstate\Command $command */
                $command = new Project\Reinstate\Command($member->getId());

                try {
                    $handler->handle($command);
                } catch (DomainException $exception) {
                    $this->logger->warning($message = $exception->getMessage(), ['exception' => $exception]);
                    $this->addFlash('error', $message);
                }
            }

        }

        return $this->redirectToRoute('pm_work_member', ['id' => $member->getId()]);
    }
}
