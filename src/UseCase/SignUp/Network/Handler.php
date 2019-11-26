<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Network;

use App\Entity\Network;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\SignUp\Request
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
        /** @var Network $network */
        $network = (new Network)
            ->setNetwork($command->network)
            ->setIdentity($command->identity);

        if ($this->em->getRepository(User::class)->findOneByNetwork($network)) {
            throw new \DomainException('User already exists.');
        }

        /** @var User $user */
        $user = (new User)
            ->signUpByNetwork($network);

        $this->em->persist($user);
        $this->em->flush();
    }
}