<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Email\Request;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Exception\DomainException;
use App\Model\Email;
use App\Service\Sender\EmailChangingSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Class Handler
 * @package App\UseCase\User\Email\Request
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
     * @throws TransportExceptionInterface
     * @throws \Throwable
     */
    public function handle(Command $command): void
    {
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneByEmail($email)) {
            throw DomainException::emailIsAlreadyInUse();
        }

        /** @var User $user */
        $user = $this->em->find(User::class, $command->id);
        $user->requestEmailChanging($email, EmbeddedToken::create());
        $this->em->flush();

        $this->sender->send($user);
    }
}