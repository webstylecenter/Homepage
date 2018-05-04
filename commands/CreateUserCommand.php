<?php

namespace Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Knp\Command\Command;

class CreateUserCommand extends Command
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @param \Silex\Application $app
     */
    public function __construct($app)
    {
        parent::__construct();
        $this->app = $app;
    }

    protected function configure()
    {
        $this->setName('app:user:create');
        $this->setDescription('Create a new user');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Feednews.me | Create a user wizard</info>');

        $userQuestion = new Question('Please enter a username: ');
        $passwordQuestion = new Question('Please enter a password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $username = $helper->ask($input, $output, $userQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);

        /** @var \Service\UserService $userService */
        $userService = $this->app['userService'];

        if ($userService->createUser($username, $password)) {
            $output->writeln('<info>User with username ' . $username . ' created!</info>');
        } else {
            $output->writeln('<error>Cannot create user ' . $username . ', probably already in use</error>');
        }
    }
}
