<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Role;

use App\Entity\User;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Role
 */
class Command
{
    /**
     * @var UuidInterface
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $roles;

    /**
     * Command constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
    }

    /**
     * @param User $user
     * @return Command
     */
    public static function parse(User $user): self
    {
        /** @var self $command */
        $command = new self($user);
        $command->roles = $user->getRoles();
        return $command;
    }
}