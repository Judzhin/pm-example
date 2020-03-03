<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\PasswordReset\Request;

use App\Entity\User;
use App\Model\Email;
use App\Service\Sender\PasswordResetSender;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\User\PasswordReset\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var TokenGenerator */
    protected $tokenizer;

    /** @var PasswordResetSender */
    protected $sender;

    /**
     * Handler constructor.
     *
     * @param EntityManagerInterface $em
     * @param TokenGenerator $tokenizer
     * @param PasswordResetSender $sender
     */
    public function __construct(EntityManagerInterface $em, TokenGenerator $tokenizer, PasswordResetSender $sender)
    {
        $this->em = $em;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     * @throws \Throwable
     */
    public function handle(Command $command): void
    {
        /** @var UserInterface|User $user */
        $user = $this->em
            ->getRepository(User::class)
            ->findOneByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate()
        );

        $this->sender->send($user);
        $this->em->flush();
    }
}