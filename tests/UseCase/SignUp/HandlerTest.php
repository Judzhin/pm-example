<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\UseCase\SignUp;

use App\Entity\User;
use App\UseCase\SignUp\Command;
use App\UseCase\SignUp\Handler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class HandlerTest
 * @package App\Tests\UseCase\SignUp
 */
class HandlerTest extends TestCase
{

    /**
     * @expectedException \DomainException
     */
    public function testFailure()
    {
        /** @var EntityManagerInterface|ObjectProphecy $em */
        $em = $this->prophesize(EntityManagerInterface::class);

        /** @var EntityRepository|ObjectProphecy $repository */
        $repository = $this->prophesize(EntityRepository::class);
        $repository
            ->findOneBy(['email' => $email = 'test@example.com'])
            ->willReturn($this->prophesize(User::class));
        $em
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var Handler $handler */
        $handler = new Handler($em->reveal());

        /** @var Command|ObjectProphecy $command */
        $command = $this->prophesize(Command::class);
        $command->email =  $email;
        $command->password = 'secret';

        $handler->handle($command->reveal());

        $this->assertTrue(true);
    }

    public function testSuccess()
    {
        /** @var EntityManagerInterface|ObjectProphecy $em */
        $em = $this->prophesize(EntityManagerInterface::class);

        $em
            ->persist(Argument::type(User::class))
            ->willReturn();

        $em
            ->flush()
            ->willReturn();

        /** @var EntityRepository|ObjectProphecy $repository */
        $repository = $this->prophesize(EntityRepository::class);
        $repository
            ->findOneBy(['email' => $email = 'test@example.com'])
            ->willReturn();
        $em
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var Handler $handler */
        $handler = new Handler($em->reveal());

        /** @var Command|ObjectProphecy $command */
        $command = $this->prophesize(Command::class);
        $command->email =  $email;
        $command->password = 'secret';

        $handler->handle($command->reveal());

        $this->assertTrue(true);
    }
}