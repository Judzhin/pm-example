<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Reset\Password;

/**
 * Class Command
 * @package App\UseCase\Reset\Password
 */
class Command
{
    /** @var string */
    public $token;

    /** @var string */
    public $password;
}