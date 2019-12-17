<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\Entity\User;
use App\Exception\DomainException;
use App\Security\FormLoginAuthenticator;
use App\UseCase\User\SignUp;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * RegistrationController constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/register", name="pm_register")
     *
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function register(Request $request, SignUp\Request\Handler $handler): Response
    {
        /** @var SignUp\Request\Command $command */
        $command = new SignUp\Request\Command;

        /** @var FormInterface $form */
        $form = $this->createForm(SignUp\Request\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $command->plainPassword = $form->get('plainPassword')->getData();
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
            } catch (DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup/{token}", name="pm_register_confirm")
     *
     * @param string $token
     * @param SignUp\Confirm\Handler $handler
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param UserProviderInterface $userProvider
     * @param Request $request
     * @param FormLoginAuthenticator $loginAuthenticator
     * @return Response
     */
    public function confirm(
        string $token, SignUp\Confirm\Handler $handler,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        UserProviderInterface $userProvider,
        Request $request,
        FormLoginAuthenticator $loginAuthenticator
    ): Response
    {
        /** @var SignUp\Confirm\Command $command */
        $command = new SignUp\Confirm\Command($token);

        try {
            /** @var User $user */
            $user = $handler->handle($command);
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $userProvider->loadUserByUsername($user->getEmail()->getValue()),
                $request,
                $loginAuthenticator,
                'main'
            );

            // $this->addFlash('success', 'Email is successfully confirmed.');
        } catch (\DomainException $e) {
            $this->logger->error($message = $e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $message);
        }

        return $this->redirectToRoute('pm_register');
    }
}