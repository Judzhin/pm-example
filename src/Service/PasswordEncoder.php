<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class PasswordEncoder
 * @package App\Service
 */
class PasswordEncoder implements PasswordEncoderInterface
{
    /** @const COST int  */
    const COST = 12;

    // /**
    //  * @param string $password
    //  * @return string
    //  * @deprecated use encoderPassword
    //  */
    // public function hash(string $password): string
    // {
    //     /** @var string $hash */
    //     if ($hash = password_hash($password, PASSWORD_ARGON2I, ['cost' => self::COST])) {
    //         return $hash;
    //     }
    //
    //     throw new BadCredentialsException('Unable to generate hash.');
    // }

    /**
     * @inheritdoc
     *
     * @param string $raw
     * @param null|string $salt
     * @return string
     */
    public function encodePassword(string $raw, ?string $salt = null): string
    {
        /** @var string $result */
        if ($result = password_hash($raw, PASSWORD_ARGON2I, ['cost' => self::COST])) {
            return $result;
        }

        throw new BadCredentialsException('Unable to generate hash.');
    }

    /**
     * @inheritdoc
     *
     * @param string $encoded
     * @param string $raw
     * @param null|string $salt
     * @return bool
     */
    public function isPasswordValid(string $encoded, string $raw, ?string $salt = null): bool
    {
        return password_verify($raw, $encoded);
    }

    /**
     * Checks if an encoded password would benefit from rehashing.
     */
    public function needsRehash(string $encoded): bool
    {
        // TODO: Implement needsRehash() method.
    }
}