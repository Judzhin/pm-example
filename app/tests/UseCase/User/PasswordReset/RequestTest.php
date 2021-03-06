<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\UseCase\User\PasswordReset;

use App\Entity\EmbeddedToken;
use App\Entity\User;
use App\Model\Email;
use App\Repository\UserRepository;
use App\Service\Sender\PasswordResetSender;
use App\Service\TokenGenerator;
use App\UseCase\User\PasswordReset\Request\Command;
use App\UseCase\User\PasswordReset\Request\Handler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RequestTest
 * @package App\Tests\UseCase\Reset
 */
class RequestTest extends TestCase
{
    public function testSuccess()
    {
        /** @var EntityManagerInterface|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManagerInterface::class);

        /** @var UserInterface|User|ObjectProphecy $object */
        $object = $this->prophesize(User::class);

        /** @var UserRepository|ObjectProphecy $repository */
        $repository = $this->prophesize(UserRepository::class);

        $entityManager
            ->getRepository(Argument::type('string'))
            ->willReturn($repository);

        /** @var Command $command */
        $command = new Command;
        $command->email = 'test@example.com';

        $repository
            ->findOneByEmail($email = new Email($command->email))
            ->willReturn($object);

        $repository
            ->findOneBy(['email' => $email])
            ->willReturn($object);

        /** @var TokenGenerator $passwordResetToken */
        $passwordResetToken = $this->prophesize(TokenGenerator::class);
        $passwordResetToken
            ->generate()
            ->willReturn($this->prophesize(EmbeddedToken::class));

        $object
            ->requestPasswordReset(Argument::type(EmbeddedToken::class))
            ->willReturn($object);

        $entityManager
            ->flush()
            ->willReturn();

        /** @var PasswordResetSender|ObjectProphecy $sender */
        $sender = $this->prophesize(PasswordResetSender::class);

        $sender
            ->send(Argument::type(User::class))
            ->willReturn();

        /** @var Handler $handler */
        $handler = new Handler(
            $entityManager->reveal(),
            $passwordResetToken->reveal(),
            $sender->reveal()
        );

        $handler->handle($command);

        $this->assertTrue(true);
    }
}