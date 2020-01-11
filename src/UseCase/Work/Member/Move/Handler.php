<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Move;

use App\Entity\Work\Member;
use App\Entity\Work\Member\Group;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Member\Move
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
        /** @var Member $member */
        $member = $this->em->find(Member::class, $command->id);
        $member->setGroup($this->em->find(Group::class, $command->group));
        $this->em->flush();
    }
}