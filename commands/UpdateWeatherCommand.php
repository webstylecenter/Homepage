<?php

namespace Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\Command\Command;

class UpdateWeatherCommand extends Command
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
        $this->setName('app:weather:update');
        $this->setDescription('Update and import weather');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Importing weather...</comment>');
        $this->app['weatherService']->updateForecast();
        $output->writeln('<info>Weather imported!</info>');
    }
}
