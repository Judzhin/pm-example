<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\Entity;
use App\Exception\DomainException;
use App\Repository\UserRepository;
use App\UseCase\User\Role;
use App\UseCase\User\SignUp;
use App\UseCase\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @package App\Controller
 *
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /** @const PER_PAGE */
    private const PER_PAGE = 10;

    /** @var LoggerInterface */
    private $logger;

    /**
     * UsersController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("", name="pm_users")
     *
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     */
    public function index(Request $request, UserRepository $repository)
    {
        /** @var User\Filter\Filter $filter */
        $filter = new User\Filter\Filter;
        $form = $this->createForm(User\Filter\FormType::class, $filter);
        $form->handleRequest($request);

        /** @var PaginationInterface $pagination */
        $pagination = $repository->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE
        );

        return $this->render(
            'users/index.html.twig',
            [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/create", name="pm_user_create")
     *
     * @param Request $request
     * @param User\Create\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, User\Create\Handler $handler): Response
    {
        /** @var User\Create\Command $command */
        $command = new User\Create\Command;

        /** @var FormInterface $form */
        $form = $this->createForm(User\Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('pm_users');
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render(
            'users/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="pm_user_show")
     *
     * -Route("/{user_id}")
     * -ParamConverter("user", options={"user_id" = "id"}, class="App\Entity\User")
     *
     * -Route("/{user_id}")
     * -Entity("user", expr="repository.find(user_id)")
     *
     * @param Entity\User $user
     * @return Response
     */
    public function show(Entity\User $user): Response
    {
        // if (!$user) {
        //     throw $this->createNotFoundException();
        // }

        return $this->render(
            'users/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="pm_user_edit")
     *
     * @param Entity\User $user
     * @param Request $request
     * @param User\Edit\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function edit(Entity\User $user, Request $request, User\Edit\Handler $handler): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $user->getId()];

        if ($user->getId()->toString() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to edit yourself.');
            return $this->redirectToRoute('pm_user_show', $parameters);
        }

        /** @var User\Edit\Command $command */
        $command = User\Edit\Command::parse($user);

        /** @var FormInterface $form */
        $form = $this->createForm(User\Edit\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'User was successfully updated');
                return $this->redirectToRoute('pm_user_show', $parameters);
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render(
            'users/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/roles", name="pm_user_roles")
     *
     * @param Entity\User $user
     * @param Request $request
     * @param Role\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function roles(Entity\User $user, Request $request, Role\Handler $handler): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $user->getId()];

        if ($user->getId()->toString() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to change roles for yourself.');
            return $this->redirectToRoute('pm_user_show', $parameters);
        }

        /** @var Role\Command $command */
        $command = Role\Command::parse($user);

        /** @var FormInterface $form */
        $form = $this->createForm(Role\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Roles was successfully updated');

                return $this->redirectToRoute('pm_user_show', $parameters);
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render(
            'users/roles.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/confirm", name="pm_user_confirm", methods={"POST"})
     *
     * @param Entity\User $user
     * @param Request $request
     * @return Response
     */
    public function confirm(Entity\User $user, Request $request): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $user->getId()];

        if ($user->getId()->toString() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to confirm for yourself.');

            return $this->redirectToRoute('pm_user_show', $parameters);
        }

        if ($this->isCsrfTokenValid('confirm', $request->request->get('_token'))) {
            try {
                $user->confirm();
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->redirectToRoute('pm_user_show', $parameters);
    }

    /**
     * @Route("/{id}/lock", name="pm_user_lock", methods={"POST"})
     *
     * @param Entity\User $user
     * @param Request $request
     * @return Response
     */
    public function lock(Entity\User $user, Request $request): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $user->getId()];

        if ($user->getId()->toString() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to lock yourself.');

            return $this->redirectToRoute('pm_user_show', $parameters);
        }

        if ($this->isCsrfTokenValid('lock', $request->request->get('_token'))) {
            try {
                $user->locking();
                $this->getDoctrine()->getManager()->flush();
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->redirectToRoute('pm_user_show', $parameters);
    }

    /**
     * @Route("/{id}/unlock", name="pm_user_unlock", methods={"POST"})
     *
     * @param Entity\User $user
     * @param Request $request
     * @return Response
     */
    public function unlock(Entity\User $user, Request $request): Response
    {
        /** @var array $parameters */
        $parameters = ['id' => $user->getId()];

        if ($user->getId()->toString() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Unable to unlock yourself.');

            return $this->redirectToRoute('pm_user_show', $parameters);
        }

        if ($this->isCsrfTokenValid('unlock', $request->request->get('_token'))) {
            try {
                $user->unlock();
                $this->getDoctrine()->getManager()->flush();
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->redirectToRoute('pm_user_show', $parameters);
    }
}
