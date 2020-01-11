<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Reinstate;

use App\Entity\Work\Member;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Member\Reinstate
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
        $member->reinstate();

        $this->em->flush();
    }
}