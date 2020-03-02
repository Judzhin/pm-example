<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Department\Remove;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package App\UseCase\Work\Project\Department\Remove
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
     * Command constructor.
     *
     * @param UuidInterface $id
     */
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }
}