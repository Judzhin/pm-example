<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker
 * @package App\Security
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * @inheritdoc
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof UserIdentity) {
            return;
        }

        if (!$user->isActive()) {
            /** @var DisabledException $exception */
            $exception = new DisabledException('User account is disabled.');
            $exception->setUser($user);
            throw $exception;
        }
    }

    /**
     * @inheritdoc
     *
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof UserIdentity) {
            return;
        }
    }
}