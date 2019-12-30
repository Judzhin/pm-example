<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Exception;

/**
 * Class LogicException
 * @package App\Exception
 */
class LogicException extends \MSBios\Exception\LogicException
{
    /**
     * @return \Throwable
     */
    public static function userIsNotFound(): \Throwable
    {
        return self::create('User is not found.');
    }
}