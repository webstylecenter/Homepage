<?php

namespace App\Command;

use App\Service\FeedService;
use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFeedsCommand extends Command
{
    /**
     * @var ImportService
     */
    protected $importService;

    /**
     * UpdateFeedsCommand constructor.
     * @param ImportService $importService
     */
    public function __construct(ImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    protected function configure()
    {
        $this
            ->setName('app:feeds:update')
            ->setDescription('Loads new contents from the feeds');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Importing feeds...</comment>');
        $this->importService->import(
            function($feedName) use ($output) {
                $output->writeln('<fg=cyan>Feed imported: ' . $feedName . '</>');
            }, function($feedName, \Exception $exception) use ($output) {
                $output->writeln('<error>Feed ' . $feedName . ' import failed with reason: ' . $exception->getMessage() . '</error>');
            }
        );

        $output->writeln('<info>Feeds imported!</info>');
    }
}