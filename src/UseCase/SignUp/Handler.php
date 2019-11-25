<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\SignUp
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        /** @var string $email */
        $email = mb_strtolower($command->email);

        if ($this->em->getRepository(User::class)->findOneBy(['email' => $email])) {
            throw new \DomainException('User already exists.');
        }

        /** @var User $user */
        $user = (new User)
            ->setEmail($email)
            ->setPassword(password_hash($command->password, PASSWORD_ARGON2I, ['cost' => 12]));

        $this->em->persist($user);
        $this->em->flush();
    }
}