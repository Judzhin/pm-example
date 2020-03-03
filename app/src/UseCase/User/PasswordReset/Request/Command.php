<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\PasswordReset\Request;

/**
 * Class Command
 * @package App\UseCase\User\PasswordReset\Request
 */
class Command
{
    /** @var string */
    public $email;
}