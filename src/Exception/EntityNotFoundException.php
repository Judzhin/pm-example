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
class EntityNotFoundException extends \MSBios\Exception\LogicException
{
    /**
     * @return \Throwable
     */
    public static function userIsNotFound(): \Throwable
    {
        return self::create('User is not found.');
    }

    /**
     * @return \Throwable
     */
    public static function groupIsNotFound(): \Throwable
    {
        return self::create('Group is not found.');
    }
}