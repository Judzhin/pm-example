<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\Entity;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\User\Email;
use App\Tests\Builder\Entity\UserBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testInstance()
    {
        /** @var UserInterface|User $user */
        $user = $user = (new UserBuilder)->viaEmail(
            $email = new Email('test@example.com'),
            $password = 'secret',
            $confirmToken = EmbeddedToken::create()
        )->build();

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUsername());
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * @throws \Exception
     */
    public function testSuccessResetPassword()
    {
        /** @var UserInterface|User $user */
        $user = (new UserBuilder)
            ->viaEmail()
            ->activate()
            ->build();

        $user->requestPasswordReset(EmbeddedToken::create());
        $this->assertNotNull($user->getResetToken()->getValue());

        $user->passwordReset(new \DateTimeImmutable, $password = 'hash');

        $this->assertNull($user->getResetToken());
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * @throws \Exception
     */
    public function testExpiredToken()
    {
        /** @var UserInterface|User $user */
        $user = (new UserBuilder)
            ->viaEmail()
            ->activate()
            ->build();

        $user->requestPasswordReset(EmbeddedToken::create(new \DateInterval("P1D")));
        $this->assertNotNull($user->getResetToken()->getValue());

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset((new \DateTimeImmutable)->add(new \DateInterval("P2D")), $password = 'hash');
    }

    /**
     * @throws \Exception
     */
    public function testNotRequested()
    {
        /** @var UserInterface|User $user */
        $user = (new UserBuilder)->viaEmail()->build();
        $this->expectExceptionMessage('Resetting is not requested.');
        $user->passwordReset((new \DateTimeImmutable)->add(new \DateInterval("P2D")), $password = 'hash');
    }
}