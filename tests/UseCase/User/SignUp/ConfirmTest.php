<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Tests\UseCase\SignUp;

use App\Entity\User;
use App\Repository\UserRepository;
use App\UseCase\User\SignUp\Confirm\Command;
use App\UseCase\User\SignUp\Confirm\Handler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfirmTest
 * @package App\Tests\UseCase\SignUp
 */
class ConfirmTest extends TestCase
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EntityRepository */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->repository = $this->prophesize(UserRepository::class);

        $this->entityManager
            ->getRepository(User::class)
            ->willReturn($this->repository);

        $this->entityManager
            ->flush()
            ->willReturn();
    }

    /**
     * @expectedException \DomainException
     */
    public function testFailure()
    {
        $this->repository
            ->findOneBy(['confirmToken.value' => 'token'])
            ->willReturn(null);

        /** @var Command $command */
        $command = new Command('token');

        $handler = new Handler(
            $this->entityManager->reveal()
        );

        $handler->handle($command);
    }

    /**
     *
     */
    public function testSuccess()
    {
        /** @var User $user */
        $user = $this->prophesize(User::class);
        $user
            ->confirm()
            ->willReturn($user);

        $this->repository
            ->findOneBy(['confirmToken.value' => 'token'])
            ->willReturn($user);

        /** @var Command $command */
        $command = new Command('token');

        $handler = new Handler(
            $this->entityManager->reveal()
        );

        $handler->handle($command);
        $this->assertTrue(true);
    }
}