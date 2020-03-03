<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\SignUp\Request;

use App\Entity\Name;
use App\Entity\User;
use App\Exception\DomainException;
use App\Model\User\Email;
use App\Service\PasswordEncoder;
use App\Service\Sender\SignUpTokenSender;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Class Handler
 * @package App\UseCase\User\SignUp\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordEncoder */
    protected $passwordEncoder;

    /** @var SignUpTokenSender */
    protected $sender;

    /**
     * Handler constructor.
     *
     * @param EntityManagerInterface $em
     * @param PasswordEncoder $passwordEncoder
     * @param SignUpTokenSender $sender
     */
    public function __construct(EntityManagerInterface $em, PasswordEncoder $passwordEncoder, SignUpTokenSender $sender)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
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
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneByEmail($email)) {
            throw DomainException::userAlreadyExists();
        }

        /** @var User $user */
        $user = User::signUpByEmail(
            new Name(
                $command->firstName,
                $command->lastName
            ),
            $email,
            $this->passwordEncoder->encodePassword($command->plainPassword)
        );

        $this->em->persist($user);
        $this->sender->send($user);
        $this->em->flush();
    }
}