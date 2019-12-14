<?php

namespace App\Controller\Profile;

use App\Entity;
use App\Exception\DomainException;
use App\UseCase\Name;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NameController
 * @package App\Controller\Profile
 */
class NameController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * NameController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/profile/name", name="pm_profile_name")
     *
     * @param Request $request
     * @param Name\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function request(Request $request, Name\Handler $handler)
    {
        /** @var Entity\User $user */
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->find(Entity\User::class, $this->getUser()->getId());

        /** @var Name\Command $command */
        $command = new Name\Command($user->getId());

        if ($user->getName() instanceof Entity\Name) {
            /** @var Entity\Name $name */
            $name = $user->getName();
            $command->firstName = $name->getFirst();
            $command->lastName = $name->getLast();
        }

        /** @var FormInterface $form */
        $form = $this->createForm(Name\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('pm_profile');
            } catch (DomainException $exception) {
                $this->logger->error($message = $exception->getMessage(), ['exception' => $exception]);
                $this->addFlash('error', $message);
            }
        }

        return $this->render('profile/name.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
