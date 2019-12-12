<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Role;

use App\Entity\User;
use App\Model\Role;
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
        // $user->changeRole(new Role($command->role));
        $user->setRoles([$command->role]);
        $this->em->flush();
    }
}