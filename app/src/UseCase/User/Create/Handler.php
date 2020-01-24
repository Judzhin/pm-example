<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Create;

use App\Entity\Name;
use App\Entity\User;
use App\Exception\DomainException;
use App\Model\User\Email;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\User\Create
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
    public function handle(Command $command)
    {

        /** @var Email $email */
        $email = new Email($command->email);

        if ($this->em->getRepository(User::class)->findOneByEmail($email)) {
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

        $this->em->persist($user);
        $this->em->flush();
    }
}