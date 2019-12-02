<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Role;

use App\Entity\User;
use App\Model\Role;
use App\Model\User\Email;
use App\Service\PasswordEncoder;
use App\Service\PasswordResetSender;
use App\Service\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\Role
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
     * @throws \Exception
     */
    public function handle(Command $command): void
    {
        /** @var UserInterface|User $user */
        $user = $this->em->find(User::class, $command->id);
        $user->changeRole(new Role($command->role));
        $this->em->flush();
    }
}