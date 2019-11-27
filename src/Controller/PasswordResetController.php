<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\UseCase\Reset;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/reset", name="pm_password_reset")
     *
     * @param Request $request
     * @param Reset\Request\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function request(Request $request, Reset\Request\Handler $handler): Response
    {
        /** @var Reset\Request\Command $command */
        $command = new Reset\Request\Command;

        /** @var FormInterface $form */
        $form = $this->createForm(Reset\Request\FormType::class, $command);
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

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}