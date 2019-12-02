<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\PasswordReset\Confirm;

use App\Entity\User;
use App\Service\PasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\PasswordReset\Confirm
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordEncoder */
    protected $passwordEncoder;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param PasswordEncoder $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, PasswordEncoder $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        /** @var UserInterface|User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['resetToken.value' => $command->token]);

        $user->passwordReset(
            new \DateTimeImmutable,
            $this->passwordEncoder->encodePassword($command->plainPassword)
        );

        $this->em->flush();
    }
}