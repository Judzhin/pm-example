<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends TestCase
{
    public function testInstance()
    {
        /** @var UserInterface $user */
        $user = (new User)
            ->setEmail($email = 'test@example.com')
            ->setPassword($password = 'secret');

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertEquals($email, $user->getUsername());
        $this->assertEquals($password, $user->getPassword());

    }
}