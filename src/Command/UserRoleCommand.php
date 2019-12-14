<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Command;

use App\Entity\User;
use App\Model\Role as RoleValue;
use App\Model\User\Email;
use App\Repository\UserRepository;
use App\UseCase\Role;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserRoleCommand
 * @package App\Command
 */
class UserRoleCommand extends Command
{
    /** @var UserRepository */
    private $repository;

    /** @var ValidatorInterface */
    private $validator;

    /** @var Role\Handler */
    private $handler;

    /**
     * UserRoleCommand constructor.
     * @param UserRepository $repository
     * @param ValidatorInterface $validator
     * @param Role\Handler $handler
     */
    public function __construct(UserRepository $repository, ValidatorInterface $validator, Role\Handler $handler)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->handler = $handler;
        parent::__construct('user:role');
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Changes user role');
    }

    /**
     * @inheritdoc
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {

        $helper = $this->getHelper('question');

        /** @var Email $email */
        $email = new Email(
            $helper->ask($input, $output, new Question('Email: '))
        );

        /** @var User $user */
        if (!$user = $this->repository->findOneByEmail($email)) {
            throw new \LogicException('User is not found.');
        }

        /** @var Role\Command $command */
        $command = new Role\Command($user);

        /** @var array $roles */
        $roles = [RoleValue::USER, RoleValue::ADMIN];

        /** @var string $role */
        $command->roles = [
            $helper->ask($input, $output, new ChoiceQuestion('Role:', $roles, 0))
        ];

        /** @var  $violations */
        $violations = $this->validator->validate($command);

        if ($violations->count()) {

            foreach ($violations as $violation) {
                $output->writeln('<error>'
                    . $violation->getPropertyPath()
                    . ':'
                    . $violation->getMessage()
                    .'</error>');
            }

            exit;
        }

        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        exit;
    }

}