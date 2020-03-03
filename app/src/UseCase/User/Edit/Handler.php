<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Edit;

use App\Entity\Name;
use App\Entity\User;
use App\Model\Email;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\User\Edit
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
     * @throws \Throwable
     */
    public function handle(Command $command)
    {
        /** @var User $user */
        $user = $this->em->find(User::class, $command->id);

        $user
            ->setEmail(new Email($command->email))
            ->setName(
                new Name(
                    $command->firstName,
                    $command->lastName
                )
            );

        $this->em->flush();
    }
}