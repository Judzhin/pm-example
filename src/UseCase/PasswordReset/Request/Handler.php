<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\PasswordReset\Request;

use App\Entity\User;
use App\Model\User\Email;
use App\Service\PasswordResetSender;
use App\Service\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\PasswordReset\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordResetToken */
    protected $tokenizer;

    /** @var PasswordResetSender */
    protected $sender;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param PasswordResetToken $tokenizer
     * @param PasswordResetSender $sender
     */
    public function __construct(EntityManagerInterface $em, PasswordResetToken $tokenizer, PasswordResetSender $sender)
    {
        $this->em = $em;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function handle(Command $command): void
    {
        /** @var UserInterface|User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => new Email($command->email)]);

        $user->requestPasswordReset(
            $this->tokenizer->generate()
        );

        $this->sender->send($user);
        $this->em->flush();
    }
}