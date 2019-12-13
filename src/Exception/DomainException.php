<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */


namespace App\Exception;

/**
 * Class DomainException
 * @package App\Exception
 */
class DomainException extends \DomainException
{
    /**
     * @return DomainException
     */
    public static function emailIsAlreadyInUse(): self
    {
        return self::create('Email is already in use.');
    }

    /**
     * @param string $message
     * @return DomainException
     */
    private static function create(string $message): self
    {
        return new self($message);
    }
}