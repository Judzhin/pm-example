<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Role\Edit;

use App\Entity\Work\Project;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Handler
 *
 * @package App\UseCase\Work\Project\Role\Edit
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
     * @throws \Throwable
     */
    public function handle(Command $command): void
    {
        /** @var Project\Department $department */
        $department = (new Project\Department)
            ->setId($command->id)
            ->setProject($command->project)
            ->setName($command->name);

        $command->project->editDepartment($department);
        $this->em->flush();
    }
}