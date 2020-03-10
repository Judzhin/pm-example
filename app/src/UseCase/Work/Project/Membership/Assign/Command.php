<?php

/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Membership\Assign;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package App\UseCase\Work\Project\Membership\Assign
 */
class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $project;

    /**
     * @Assert\NotBlank()
     */
    public $member;

    /**
     * @Assert\NotBlank()
     */
    public $departments;

    /**
     * @Assert\NotBlank()
     */
    public $roles;

    // public function __construct(string $project)
    // {
    //     $this->project = $project;
    // }
}