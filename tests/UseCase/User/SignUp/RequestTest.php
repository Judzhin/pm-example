<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\UseCase\SignUp;

use App\Entity\User;
use App\Model\User\Email;
use App\Repository\UserRepository;
use App\Service\Sender\SignUpTokenSender;
use App\Service\PasswordEncoder;
use App\UseCase\User\SignUp\Request\Command;
use App\UseCase\User\SignUp\Request\Handler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
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
    protected $passwordEncoder;

    /** @var Handler */
    protected $handler;

    /** @var SignUpTokenSender */
    protected $sender;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->repository = $this->prophesize(UserRepository::class);

        $this->entityManager
            ->getRepository(User::class)
            ->willReturn($this->repository);

        $this->passwordEncoder = $this->prophesize(PasswordEncoder::class);
        $this->passwordEncoder
            ->encodePassword('secret')
            ->willReturn((new PasswordEncoder)->encodePassword('secret'));

        $this->sender = $this->prophesize(SignUpTokenSender::class);
    }

    /**
     * @expectedException \DomainException
     * @throws TransportExceptionInterface
     */
    public function testFailure()
    {
        /** @var User|ObjectProphecy $object */
        $object = $this->prophesize(User::class);

        $this->repository
            ->findOneByEmail($email = new Email('test@example.com'))
            ->willReturn($object);

        $this->repository
            ->findOneBy(['email' => $email])
            ->willReturn($object);

        /** @var Command $command */
        $command = new Command;
        $command->email = $email->getValue();
        $command->plainPassword = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->passwordEncoder->reveal(),
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
            ->findOneByEmail($email =  new Email('test@example.com'))
            ->willReturn(null);

        $this->repository
            ->findOneBy(['email' => $email])
            ->willReturn(null);

        $this->sender
            ->send(Argument::type(User::class))
            ->shouldBeCalled();

        /** @var Command $command */
        $command = new Command;
        $command->firstName = 'First';
        $command->lastName = 'Last';
        $command->email = $email->getValue();
        $command->plainPassword = 'secret';

        $handler = new Handler(
            $this->entityManager->reveal(),
            $this->passwordEncoder->reveal(),
            $this->sender->reveal()
        );

        $handler->handle($command);
        $this->assertTrue(true);
    }

}