<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Exception;

use MSBios\Exception\ExceptionFactoryTrait;

/**
 * Class UnsupportedUserException
 * @package App\Exception
 */
class UnsupportedUserException extends \Symfony\Component\Security\Core\Exception\UnsupportedUserException
{
    use ExceptionFactoryTrait;

    /**
     * @param string $name
     * @return \Throwable
     */
    public static function instanceAreNotSupported(string $name): \Throwable
    {
        return self::create(sprintf('Instances of "%s" are not supported.', $name));
    }
}