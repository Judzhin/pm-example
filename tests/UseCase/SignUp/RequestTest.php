<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\UseCase\SignUp;

use App\Entity\User;
use App\Model\User\Email;
use App\Repository\UserRepository;
use App\Service\ConfirmSender;
use App\Service\PasswordHasher;
use App\UseCase\SignUp\Request\Command;
use App\UseCase\SignUp\Request\Handler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * Class RequestTest
 * @package App\Tests\UseCase\SignUp
 */
class RequestTest extends TestCase
{

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EntityRepository */
    protected $repository;

    /** @var PasswordHasher */
    protected $hasher;

    /** @var Handler */
    protected $handler;

    /** @var ConfirmSender */
    protected $sender;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->repository = $this->prophesize(UserRepository::class);

        $this->entityManager
            ->getRepository(User::class)
            ->willReturn($this->repository);

        $this->entityManager
            ->persist(Argument::type(User::class))
            ->willReturn();

        $this->entityManager
            ->flush()
            ->willReturn();

        $this->hasher = $this->prophesize(PasswordHasher::class);
        $this->hasher
            ->hash('secret')
            ->willReturn((new PasswordHasher)->hash('secret'));

        $this->sender = $this->prophesize(ConfirmSender::class);
        $this->sender
            ->send(Argument::type(User::class))
            ->willReturn();
    }

    /**
     * @expectedException \DomainException
     */
    public function testFailure()
    {
        $this->repository
            ->findOneBy(['email' => new Email($email = 'test@example.com')])
            ->willReturn(true);

        /** @var Command $command */
        $command = new Command;
        $command->email = $email;
        $command->password = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->hasher->reveal(),
            $this->sender->reveal()
        );

        $handler->handle($command);
    }

    /**
     * @throws \Exception
     */
    public function testSuccess()
    {
        $this->repository
            ->findOneBy(['email' => new Email($email = 'test@example.com')])
            ->willReturn(false);

        /** @var Command $command */
        $command = new Command;
        $command->email = $email;
        $command->password = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->hasher->reveal(),
            $this->sender->reveal()
        );

        $handler->handle($command);
        $this->assertTrue(true);
    }

}