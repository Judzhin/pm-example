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
    use FactoryExceptionTrait;

    /**
     * @return DomainException
     */
    public static function userIsAlreadyConfirmed(): self
    {
        return self::factory('User is already confirmed.');
    }

    /**
     * @return DomainException
     */
    public static function userIsAlreadyLocked(): self
    {
        return self::factory('User is already locked.');
    }

    /**
     * @return DomainException
     */
    public static function userIsAlreadyUnlocked(): self
    {
        return self::factory('User is already unlocked.');
    }

    /**
     * @return DomainException
     */
    public static function userWithThisEmailAlreadyExists(): self
    {
        return self::factory('User with this email already exists.');
    }

    /**
     * @return DomainException
     */
    public static function userAlreadyExists(): self
    {
        return self::factory('User already exists.');
    }

    /**
     * @return DomainException
     */
    public static function incorrectOrConfirmedToken(): self
    {
        return self::factory('Incorrect or confirmed token.');
    }

    /**
     * @return DomainException
     */
    public static function emailIsAlreadyInUse(): self
    {
        return self::factory('Email is already in use.');
    }
}