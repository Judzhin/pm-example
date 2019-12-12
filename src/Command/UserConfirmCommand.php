<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Command;

use App\Entity\User;
use App\Model\User\Email;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class UserConfirmCommand
 * @package App\Command
 */
class UserConfirmCommand extends Command
{
    /** @var UserRepository */
    private $repository;

    /**
     * UserConfirmCommand constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('user:confirm')
            ->setDescription('Confirm signed up user');
    }

    /**
     * @inheritdoc
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var  $helper */
        $helper = $this->getHelper('question');

        /** @var Email $email */
        $email = new Email(
            $helper->ask($input, $output, new Question('Email: '))
        );

        /** @var User $user */
        if (!$user = $this->repository->findOneByEmail($email)) {
            throw new \LogicException('User is not found.');
        }

        $user->confirmSignUp();
        $output->writeln('<info>Done!</info>>');
    }

}