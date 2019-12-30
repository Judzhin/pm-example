<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Profile;

use App\Exception\DomainException;
use App\UseCase\User\Email;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EmailController
 * @package App\Controller\Profile
 *
 * @Route("/profile/email")
 */
class EmailController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * EmailController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="pm_profile_email")
     *
     * @param Request $request
     * @param Email\Request\Handler $handler
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function request(Request $request, Email\Request\Handler $handler): Response
    {
        /** @var Email\Request\Command $command */
        $command = new Email\Request\Command($this->getUser()->getId());

        /** @var FormInterface $form */
        $form = $this->createForm(Email\Request\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('pm_profile');
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('profile/email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{token}", name="pm_profile_email_confirm")
     *
     * @param string $token
     * @param Email\Confirm\Handler $handler
     * @return RedirectResponse
     */
    public function confirm(string $token, Email\Confirm\Handler $handler): RedirectResponse
    {
        /** @var Email\Confirm\Command $command */
        $command = new Email\Confirm\Command($this->getUser()->getId(), $token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully changed.');
        } catch (DomainException $exception) {
            $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('pm_profile');
    }
}
