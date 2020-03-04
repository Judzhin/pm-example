<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Role\Create;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Project\Role\Create
 */
class Command
{
    /** @var string */
    public $name;

    /** @var array */
    public $permissions;

}