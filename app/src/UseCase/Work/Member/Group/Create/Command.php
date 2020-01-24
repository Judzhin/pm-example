<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Group\Create;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Member\Group\Create
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}