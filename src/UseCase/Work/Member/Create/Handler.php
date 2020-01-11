<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Create;

use App\Entity\Name;
use App\Entity\Work\Member;
use App\Entity\Work\Member\Group;
use App\Exception\DomainException;
use App\Model\Email;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Member\Create
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
    public function handle(Command $command): void
    {
        if ($this->em->find(Member::class, $command->id)) {
            throw DomainException::memberAlreadyExists();
        }

        /** @var Group $group */
        $group = $this->em->find(Group::class, $command->group);

        /** @var Member $member */
        $member = (new Member)
            ->setId($command->id)
            ->setGroup($group)
            ->setEmail(new Email($command->email))
            ->setName(
                new Name(
                    $command->firstName,
                    $command->lastName
                )
            );

        $this->em->persist($member);
        $this->em->flush();
    }
}