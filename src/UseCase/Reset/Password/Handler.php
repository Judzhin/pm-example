<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Reset\Password;

use App\Entity\User;
use App\Model\User\Email;
use App\Service\PasswordHasher;
use App\Service\PasswordResetSender;
use App\Service\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\Reset\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PasswordHasher */
    protected $hasher;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $em
     * @param PasswordHasher $hasher
     */
    public function __construct(EntityManagerInterface $em, PasswordHasher $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
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
            $this->hasher->hash($command->password)
        );

        $this->em->flush();
    }
}