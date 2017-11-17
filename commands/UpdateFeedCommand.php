<?php

namespace Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\Command\Command;

class UpdateFeedCommand extends Command
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
        $this->setName('app:feed:update');
        $this->setDescription('Update and import ATOM feeds');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Importing feeds...</comment>');
        $this->app['feedService']->import(function($feedName) use ($output) {
            $output->writeln('<fg=cyan>Feed imported: ' . $feedName . '</>');
        });

        $output->writeln('<info>Feeds imported!</info>');
    }
}
