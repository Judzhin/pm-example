<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Role;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Role
 */
class Command
{
    /**
     * @var null|\Ramsey\Uuid\UuidInterface
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $role;

    /**
     * Command constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
    }
}