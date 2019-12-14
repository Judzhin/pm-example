<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Name;

use App\Entity\EmbeddedToken;
use App\Entity\Name;
use App\Entity\User;
use App\Exception\DomainException;
use App\Model\User\Email;
use App\Service\PasswordEncoder;
use App\Service\Sender\SignUpTokenSender;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Name
 */
class Handler
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * Handler constructor.
     *
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
        /** @var User $user */
        $user = $this->em->find(User::class, $command->id);
        $user->setName(new Name(
            $command->firstName,
            $command->lastName
        ));
        $this->em->persist($user);
        $this->em->flush();
    }
}