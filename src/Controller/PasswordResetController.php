<?php

namespace App\Controller;

use App\Entity\User;
use App\UseCase\User\PasswordReset;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PasswordResetController
 * @package App\Controller
 */
class PasswordResetController extends AbstractController
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * PasswordResetController constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/password-reset", name="pm_password_reset")
     *
     * @param Request $request
     * @param PasswordReset\Request\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function request(Request $request, PasswordReset\Request\Handler $handler)
    {
        /** @var PasswordReset\Request\Command $command */
        $command = new PasswordReset\Request\Command;

        /** @var FormInterface $form */
        $form = $this->createForm(PasswordReset\Request\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('password_reset/request.html.twig', [
            'passwordResetForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password-reset/{token}", name="pm_password_reset_confirm")
     *
     * @param string $token
     * @param Request $request
     * @param PasswordReset\Confirm\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function confirm(string $token, Request $request, PasswordReset\Confirm\Handler $handler)
    {

        if (!$this->getDoctrine()->getManager()->getRepository(User::class)->count(['resetToken.value' => $token])) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');
            return $this->redirectToRoute('pm_home');
        }

        /** @var PasswordReset\Confirm\Command $command */
        $command = new PasswordReset\Confirm\Command($token);

        /** @var FormInterface $form */
        $form = $this->createForm(PasswordReset\Confirm\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Password is successfully changed.');
                $this->redirectToRoute('pm_home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'exceptions'));
            }
        }

        return $this->render('password_reset/confirm.html.twig', [
            'passwordConfirmForm' => $form->createView(),
        ]);
    }
}
