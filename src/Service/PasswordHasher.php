<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

/**
 * Class PasswordHasher
 * @package App\Service
 */
class PasswordHasher
{
    /** @const COST int  */
    const COST = 12;

    /**
     * @param string $password
     * @return string
     */
    public function hash(string $password): string
    {
        /** @var string $hash */
        if ($hash = password_hash($password, PASSWORD_ARGON2I, ['cost' => self::COST])) {
            return $hash;
        }

        throw new \RuntimeException('Unable to generate hash.');
    }
}