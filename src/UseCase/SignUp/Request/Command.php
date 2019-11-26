<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Request;

/**
 * Class Command
 * @package App\UseCase\SignUp\Request
 */
class Command
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;
}