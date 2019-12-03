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
use App\Service\SignUpTokenSender;
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
    protected $passwordEncoder;

    /** @var SignUpTokenSender */
    protected $sender;

    /**
     * Handler constructor.
     *
     * @param EntityManagerInterface $em
     * @param PasswordEncoder $paspasswordEncoder
     * @param SignUpTokenSender $sender
     */
    public function __construct(EntityManagerInterface $em, PasswordEncoder $paspasswordEncoder, SignUpTokenSender $sender)
    {
        $this->em = $em;
        $this->passwordEncoder = $paspasswordEncoder;
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
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        /** @var User $user */
        $user = User::signUpByEmail(
            $email,
            $this->passwordEncoder->encodePassword($command->plainPassword),
            EmbeddedToken::create()
        );

        $this->em->persist($user);
        $this->sender->send($user);
        $this->em->flush();
    }
}