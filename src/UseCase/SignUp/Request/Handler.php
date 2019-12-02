<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Request;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\User\Email;
use App\Service\PasswordEncoder;
use App\Service\ConfirmTokenSender;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\SignUp\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordEncoder */
    protected $hasher;

    /** @var ConfirmTokenSender */
    protected $sender;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param PasswordEncoder $hasher
     * @param ConfirmTokenSender $sender
     */
    public function __construct(EntityManagerInterface $em, PasswordEncoder $hasher, ConfirmTokenSender $sender)
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
            $this->hasher->hash($command->plainPassword),
            EmbeddedToken::create()
        );

        // dump($user); die;

        $this->em->persist($user);
        $this->sender->send($user);
        $this->em->flush();
    }
}