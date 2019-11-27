<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

/**
 * Class PasswordResetTokenFactory
 * @package App\Service
 */
class PasswordResetTokenFactory
{
    /**
     * @param string $interval
     * @return PasswordResetToken
     * @throws \Exception
     */
    public function create(string $interval): PasswordResetToken
    {
        return new PasswordResetToken(
            new \DateInterval($interval)
        );
    }

}