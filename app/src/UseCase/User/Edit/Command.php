<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Edit;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\User\Edit
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $lastName;

    /**
     * @param User $user
     * @return Command
     */
    public static function parse(User $user): self
    {
        /** @var self $command */
        $command = new self;
        // $command->id = $user->getId()->toString();
        $command->id = $user->getId();
        // $command->email = $user->getEmail()->getValue();
        $command->email = $user->getEmail();
        $command->firstName = $user->getName()->getFirst();
        $command->lastName = $user->getName()->getLast();
        return $command;
    }
}