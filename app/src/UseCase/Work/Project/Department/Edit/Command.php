<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Department\Edit;

use App\Entity\Work\Project;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package App\UseCase\Work\Project\Department\Edit
 */
class Command
{
    /**
     * @var UuidInterface
     *
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var Project
     *
     * @Assert\NotBlank()
     */
    public $project;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * Command constructor.
     *
     * @param UuidInterface $id
     */
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @param Project\Department $department
     * @return static
     */
    public static function parse(Project\Department $department): self
    {
        /** @var self $command */
        $command = new self($department->getId());
        $command->project = $department->getProject();
        $command->name = $department->getName();
        return $command;
    }
}