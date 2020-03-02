<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Edit;

use App\Entity\Work\Project;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Project\Edit
 */
class Command extends \App\UseCase\Work\Project\Create\Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * Command constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param Project $project
     *
     * @return static
     */
    public static function parse(Project $project): self
    {
        /** @var self $command */
        $command = new self($project->getId());
        $command->name = $project->getName();
        $command->sort = $project->getSort();
        return $command;
    }
}