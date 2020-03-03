<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Create;

use App\Entity\Name;
use App\Entity\User;
use App\Exception\DomainException;
use App\Model\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\User\Create
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $users;

    /**
     * Handler constructor.
     *
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param Command $command
     * @throws \Throwable
     */
    public function handle(Command $command)
    {
        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->users->findOneByEmail($email)) {
            throw DomainException::userWithThisEmailAlreadyExists();
        }

        /** @var User $user */
        $user = (new User)
            ->setEmail($email)
            ->setName(new Name(
                $command->firstName,
                $command->lastName
            ));
        $user->setPassword('');
        $user->confirm(true);

        $this->users->add($user);
    }
}