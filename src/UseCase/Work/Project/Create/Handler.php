<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Create;

use App\Entity\Work\Project;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Project\Create
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
        /** @var Project $project */
        $project = (new Project)
            ->setName($command->name)
            ->setSort($command->sort);

        $this->em->persist($project);
        $this->em->flush();
    }
}