<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Edit;

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

    ///**
    // * @param Member $member
    // * @return Command
    // */
    //public static function parse(Member $member): self
    //{
    //    /** @var self $command */
    //    $command = new self($member->getId());
    //    $command->firstName = $member->getName()->getFirst();
    //    $command->lastName = $member->getName()->getLast();
    //    $command->email = $member->getEmail();
    //    return $command;
    //}
}