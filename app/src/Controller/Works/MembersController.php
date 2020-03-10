<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works;

use App\Annotation\UUIDv4;
use App\Entity;
use App\Exception\DomainException;
use App\ReadModel\Work\MemberFetcher;
use App\Repository\MemberRepository;
use App\UseCase\Work\Member;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembersController
 * @package App\Controller\Work
 */
class MembersController extends AbstractController
{
    /** @const PER_PAGE */
    private const PER_PAGE = 10;

    /** @var MemberRepository */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * MembersController constructor.
     *
     * @param MemberFetcher $repository
     * @param LoggerInterface $logger
     */
    public function __construct(MemberFetcher $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @Route("/work/members", name="pm_work_members")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var Member\Filter\Filter $filter */
        $filter = new Member\Filter\Filter;
        $form = $this->createForm(Member\Filter\FormType::class, $filter);
        $form->handleRequest($request);

        /** @var PaginationInterface $pagination */
        $pagination = $this->repository->all(
            $filter, $request->query->getInt('page', 1), self::PER_PAGE
        );

        return $this->render(
            'works/members/index.html.twig',
            [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/work/member/create/{user_id}", name="pm_work_member_create")
     * @ParamConverter("user", options={"id" = "user_id"}, class="App\Entity\User")
     *
     * @param Entity\User $user
     * @param Request $request
     * @param Member\Create\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function create(Entity\User $user, Request $request, Member\Create\Handler $handler): Response
    {
        /** @var  $parameters */
        $parameters = ['id' => $user->getId()];

        if ($this->repository->find($user->getId())) {
            $this->addFlash('error', 'Member already exists.');
            return $this->redirectToRoute('pm_user', $parameters);
        }

        /** @var Member\Create\Command $command */
        $command = new Member\Create\Command($user->getId());
        $command->firstName = $user->getName()->getFirst();
        $command->lastName = $user->getName()->getLast();
        $command->email = $user->getEmail();

        /** @var FormInterface $form */
        $form = $this->createForm(Member\Create\FormType::class, $command);
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
     * @Route("/work/member/{id}", name="pm_work_member", requirements={"id"=UUIDv4::PATTERN})
     *
     * @param Entity\Work\Member $member
     * @return Response
     */
    public function show(Entity\Work\Member $member): Response
    {
        return $this->render('works/members/show.html.twig', compact('member'));
    }

    /**
     * @Route("/work/member/edit/{id}", name="pm_work_member_edit")
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Member\Edit\Handler $handler
     * @return Response
     */
    public function edit(Entity\Work\Member $member, Request $request, Member\Edit\Handler $handler): Response
    {
        /** @var Member\Edit\Command $command */
        $command = Member\Edit\Command::parse($member);

        /** @var FormInterface $form */
        $form = $this->createForm(Member\Edit\FormType::class, $command);
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
     * @Route("/work/member/move/{id}", name="pm_work_member_move")
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Member\Move\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function move(Entity\Work\Member $member, Request $request, Member\Move\Handler $handler): Response
    {
        /** @var Member\Edit\Command $command */
        $command = Member\Move\Command::parse($member);

        /** @var FormInterface $form */
        $form = $this->createForm(Member\Move\FormType::class, $command);
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

        return $this->render('works/members/move.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/work/member/archive/{id}", name="pm_work_member_archive", methods={"POST"})
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Member\Archived\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function archive(Entity\Work\Member $member, Request $request, Member\Archived\Handler $handler): Response
    {
        if ($this->isCsrfTokenValid('archive', $request->request->get('token'))) {
            /** @var Member\Archived\Command $command */
            $command = new Member\Archived\Command($member->getId());

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
     * @Route("/work/member/reinstate/{id}", name="pm_work_member_reinstate", methods={"POST"})
     *
     * @param Entity\Work\Member $member
     * @param Request $request
     * @param Member\Reinstate\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function reinstate(Entity\Work\Member $member, Request $request, Member\Reinstate\Handler $handler): Response
    {
        if ($this->isCsrfTokenValid('reinstate', $request->request->get('token'))) {

            if ($member->getId()->toString() === $this->getUser()->getId()) {
                $this->addFlash('error', 'Unable to reinstate yourself.');
            } else {
                /** @var Member\Reinstate\Command $command */
                $command = new Member\Reinstate\Command($member->getId());

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
