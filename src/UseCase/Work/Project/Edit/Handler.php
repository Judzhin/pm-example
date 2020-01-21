<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Edit;

use App\Entity\Work\Project;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 * @package App\UseCase\Work\Project\Edit
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
     */
    public function handle(Command $command): void
    {
        /** @var Project $project */
        $project = $this->em->find(Project::class, $command->id);
        $project
            ->setName($command->name)
            ->setSort($command->sort);
        $this->em->flush();
    }
}