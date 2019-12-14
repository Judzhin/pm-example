<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\Entity;
use App\Exception\DomainException;
use App\Repository\UserRepository;
use App\UseCase\Role;
use App\UseCase\User;
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
     * @param UserRepository $repository
     * @return Response
     */
    public function index(UserRepository $repository)
    {
        /** @var array $users */
        $users = $repository->findAll();

        return $this->render('users/index.html.twig', compact('users'));
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

        return $this->render('users/create.html.twig', [
            'form' => $form->createView()
        ]);
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

        return $this->render('users/show.html.twig', compact('user'));
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
        /** @var User\Edit\Command $command */
        $command = User\Edit\Command::parse($user);

        /** @var FormInterface $form */
        $form = $this->createForm(User\Edit\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'User was successfully updated');
                return $this->redirectToRoute('pm_user_show', ['id' => $user->getId()]);
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
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

        return $this->render('users/roles.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
