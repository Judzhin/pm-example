<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\UseCase\SignUp;

use App\Entity\User;
use App\Model\User\Email;
use App\Repository\UserRepository;
use App\Service\ConfirmTokenSender;
use App\Service\PasswordEncoder;
use App\UseCase\SignUp\Request\Command;
use App\UseCase\SignUp\Request\Handler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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

    /** @var PasswordEncoder */
    protected $hasher;

    /** @var Handler */
    protected $handler;

    /** @var ConfirmTokenSender */
    protected $sender;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->repository = $this->prophesize(UserRepository::class);

        $this->entityManager
            ->getRepository(User::class)
            ->willReturn($this->repository);

        $this->hasher = $this->prophesize(PasswordEncoder::class);
        $this->hasher
            ->hash('secret')
            ->willReturn((new PasswordEncoder)->hash('secret'));

        $this->sender = $this->prophesize(ConfirmTokenSender::class);
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
        $command->plainPassword = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->hasher->reveal(),
            $this->sender->reveal()
        );

        $handler->handle($command);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSuccess()
    {
        $this->entityManager
            ->persist(Argument::type(User::class))
            ->shouldBeCalled();

        $this->entityManager
            ->flush()
            ->shouldBeCalled();

        $this->repository
            ->findOneBy(['email' => new Email($email = 'test@example.com')])
            ->willReturn(false);

        $this->sender
            ->send(Argument::type(User::class))
            ->shouldBeCalled();

        /** @var Command $command */
        $command = new Command;
        $command->email = $email;
        $command->plainPassword = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->hasher->reveal(),
            $this->sender->reveal()
        );

        $handler->handle($command);
        $this->assertTrue(true);
    }

}