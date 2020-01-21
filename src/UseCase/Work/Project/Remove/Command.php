<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Remove;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Project\Remove
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
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}