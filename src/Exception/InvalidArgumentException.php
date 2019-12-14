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
class InvalidArgumentException extends \InvalidArgumentException
{
    use FactoryExceptionTrait;

    /**
     * @return InvalidArgumentException
     */
    public static function incorrectEmail(): self
    {
        return self::factory('Incorrect email');
    }
}