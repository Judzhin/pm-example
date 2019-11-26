<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Tests\Entity;

use App\Entity\User;
use App\Model\User\Email;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends TestCase
{
    public function testInstance()
    {
        /** @var UserInterface|User $user */
        $user = (new User($uuid = Uuid::uuid4()))
            ->setEmail($email = new Email('test@example.com'))
            ->setPassword($password = 'secret');

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertEquals($uuid, $user->getId());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
    }
}