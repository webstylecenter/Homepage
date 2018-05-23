<?php

namespace App\Command;

use App\Service\FeedService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFeedsCommand extends Command
{
    protected $feedService;

    /**
     * UpdateFeedsCommand constructor.
     * @param FeedService $feedService
     */
    public function __construct(FeedService $feedService)
    {
        parent::__construct();
        $this->feedService = $feedService;
    }

    protected function configure()
    {
        $this
            ->setName('app:feeds:update')
            ->setDescription('Loads new contents from the feeds')
            ->setHelp('This will download each feed and add new items to the database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Importing feeds...</comment>');
        $this->feedService->import(
            function($feedName) use ($output) {
                $output->writeln('<fg=cyan>Feed imported: ' . $feedName . '</>');
            }, function($feedName, \Exception $exception) use ($output) {
                $output->writeln('<error>Feed ' . $feedName . ' import failed with reason: ' . $exception->getMessage() . '</error>');
            }
        );

        $output->writeln('<info>Feeds imported!</info>');
    }
}