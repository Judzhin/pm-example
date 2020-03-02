<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Department\Create;

use App\Entity\Work\Project;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Project\Department\Create
 */
class Command
{
    /**
     * @var Project
     * @Assert\NotBlank()
     */
    public $project;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}