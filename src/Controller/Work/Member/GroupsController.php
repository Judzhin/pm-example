<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Controller\Work\Member;

use App\Entity;
use App\Exception\DomainException;
use App\Repository\GroupRepository;
use App\UseCase\Work\Member\Group;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupsController
 * @package App\Controller\Work\Member
 *
 * @Route("/work/member/groups")
 * @IsGranted("ROLE_WORK_MANAGE_MEMBERS")
 */
class GroupsController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * GroupsController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("", name="pm_work_member_groups")
     *
     * @param GroupRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(GroupRepository $repository)
    {
        /** @var array $groups */
        $groups = $repository->all();
        return $this->render('works/members/groups/index.html.twig', compact('groups'));
    }

    /**
     * @Route("/create", name="pm_work_member_group_create")
     *
     * @param Request $request
     * @param Group\Create\Handler $handler
     * @return Response
     */
    public function create(Request $request, Group\Create\Handler $handler): Response
    {
        /** @var Group\Create\Command $command */
        $command = new Group\Create\Command;

        /** @var FormInterface $form */
        $form = $this->createForm(Group\Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('pm_work_member_groups');
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('works/members/groups/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="pm_work_member_group_show")
     *
     * @param Entity\Work\Member\Group $group
     * @return Response
     */
    public function show(Entity\Work\Member\Group $group): Response
    {
        return $this->redirectToRoute('pm_work_member_groups');
    }

    /**
     * @Route("/{id}/edit", name="pm_work_member_group_edit")
     *
     * @param Entity\Work\Member\Group $group
     * @param Request $request
     * @param Group\Edit\Handler $handler
     * @return Response
     */
    public function edit(Entity\Work\Member\Group $group, Request $request, Group\Edit\Handler $handler): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $group->getId()];

        /** @var Group\Edit\Command $command */
        $command = Group\Edit\Command::parse($group);

        /** @var FormInterface $form */
        $form = $this->createForm(Group\Edit\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Group was successfully updated');
                return $this->redirectToRoute('pm_work_member_group_show', $parameters);
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render(
            'works/members/groups/edit.html.twig',
            [
                'group' => $group,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="pm_work_member_group_delete")
     *
     * @param Entity\Work\Member\Group $group
     * @param Request $request
     * @param Group\Remove\Handler $handler
     * @return Response
     */
    public function delete(Entity\Work\Member\Group $group, Request $request, Group\Remove\Handler $handler): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            /** @var Group\Remove\Command $command */
            $command = new Group\Remove\Command($group->getId()->toString());

            try {
                $handler->handle($command);
                return $this->redirectToRoute('pm_work_member_groups');
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->redirectToRoute('pm_work_member_group_show', ['id' => $group->getId()]);
    }
}
