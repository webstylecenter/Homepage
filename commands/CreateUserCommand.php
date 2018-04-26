<?php

namespace Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        $this->setName('app:user:add');
        $this->setDescription('Create a new user');
        $this->addArgument('username', InputArgument::REQUIRED, 'The username you would like to use');
        $this->addArgument('password', InputArgument::REQUIRED, 'A safe password for the user to sign in with');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Test...</comment>');
    }
}
