<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Remove;

use App\Entity\Work\Member\Group;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Member\Remove
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * Command constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}