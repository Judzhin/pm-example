<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\SignUp\Confirm;

use App\Entity\User;
use App\Exception\DomainException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Handler
 * @package App\UseCase\User\SignUp\Confirm
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
     * @return User
     */
    public function handle(Command $command): User
    {
        /** @var UserInterface|User $user */
        if (!$user = $this->em->getRepository(User::class)->findOneBy(['confirmToken.value' => $command->token])) {
            throw DomainException::incorrectOrConfirmedToken();
        }

        $user->confirm();
        $this->em->flush();

        return $user;
    }
}