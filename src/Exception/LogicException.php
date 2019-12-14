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
class LogicException extends \LogicException
{
    use FactoryExceptionTrait;

    /**
     * @return LogicException
     */
    public static function userIsNotFound(): self
    {
        return self::factory('User is not found.');
    }
}