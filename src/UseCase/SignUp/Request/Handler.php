<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Request;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\User\Email;
use App\Service\PasswordHasher;
use App\Service\ConfirmSender;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\SignUp\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordHasher */
    protected $hasher;

    /** @var ConfirmSender */
    protected $sender;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param PasswordHasher $hasher
     * @param ConfirmSender $sender
     */
    public function __construct(EntityManagerInterface $em, PasswordHasher $hasher, ConfirmSender $sender)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneBy(['email' => $email])) {
            throw new \DomainException('User already exists.');
        }

        /** @var User $user */
        $user = User::signUpByEmail(
            $email,
            $this->hasher->hash($command->password),
            EmbeddedToken::create()
        );

        $this->em->persist($user);
        $this->sender->send($user);
        $this->em->flush();
    }
}