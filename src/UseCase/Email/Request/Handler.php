<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Email\Request;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\User\Email;
use App\Service\Sender\EmailChangingSender;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Email\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EmailChangingSender */
    protected $sender;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param EmailChangingSender $sender
     */
    public function __construct(EntityManagerInterface $em, EmailChangingSender $sender)
    {
        $this->em = $em;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function handle(Command $command): void
    {
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneByEmail($email)) {
            throw new \DomainException('Email is already in use.');
        }

        /** @var User $user */
        $user = $this->em->find(User::class, $command->id);
        $user->requestEmailChanging($email, EmbeddedToken::create());
        $this->em->flush();

        $this->sender->send($user);
    }
}