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
class DomainException extends \MSBios\Exception\DomainException
{
    /**
     * @return \Throwable
     */
    public static function userIsAlreadyConfirmed(): \Throwable
    {
        return self::create('User is already confirmed.');
    }

    /**
     * @return \Throwable
     */
    public static function userIsAlreadyLocked(): \Throwable
    {
        return self::create('User is already locked.');
    }

    /**
     * @return \Throwable
     */
    public static function userIsAlreadyUnlocked(): \Throwable
    {
        return self::create('User is already unlocked.');
    }

    /**
     * @return \Throwable
     */
    public static function userWithThisEmailAlreadyExists(): \Throwable
    {
        return self::create('User with this email already exists.');
    }

    /**
     * @return \Throwable
     */
    public static function userAlreadyExists(): \Throwable
    {
        return self::create('User already exists.');
    }

    /**
     * @return \Throwable
     */
    public static function incorrectOrConfirmedToken(): \Throwable
    {
        return self::create('Incorrect or confirmed token.');
    }

    /**
     * @return \Throwable
     */
    public static function emailIsAlreadyInUse(): \Throwable
    {
        return self::create('Email is already in use.');
    }
}