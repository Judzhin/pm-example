<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Exception;

/**
 * Class InvalidArgumentException
 * @package App\Exception
 */
class InvalidArgumentException extends \MSBios\Exception\InvalidArgumentException
{
    /**
     * @return \Throwable
     */
    public static function incorrectEmail(): \Throwable
    {
        return self::create('Incorrect email');
    }
}