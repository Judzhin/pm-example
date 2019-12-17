<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Email\Confirm;

use App\Entity\User;
use App\Model\User\Token;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Email\Request
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * Handler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, $command->id);
        $user->confirmEmailChanging(new Token($command->token));
        $this->entityManager->flush();
    }
}