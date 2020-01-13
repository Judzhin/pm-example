<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Group\Remove;

use App\Entity\Work\Member\Group;
use App\Exception\DomainException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Member\Group\Remove
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
        /** @var Group $group */
        $group = $this->em->find(Group::class, $command->id);

        if ($group->getMembers()->count()) {
            throw DomainException::groupIsNotEmpty();
        }

        $this->em->remove($group);
        $this->em->flush();
    }
}