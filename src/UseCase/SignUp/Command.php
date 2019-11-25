<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp;

/**
 * Class Command
 * @package App\UseCase\SignUp
 */
class Command
{
    /** @var string */
    public $email;

    /** @var string */
    public $password;
}