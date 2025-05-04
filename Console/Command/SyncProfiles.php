<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Qoliber\Psl\Service\OpenSearchProfileSync;

class SyncProfiles extends Command
{
    /**
     * @param \Qoliber\Psl\Service\OpenSearchProfileSync $syncService
     * @param string|null $name
     */
    public function __construct(
        private readonly OpenSearchProfileSync $syncService,
        string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('qoliber:psl:sync-profiles')
            ->setDescription('Synchronize PSL profiles with OpenSearch');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln('<info>Starting PSL profile synchronization...</info>');
            $this->syncService->execute();
            $output->writeln('<info>PSL profile synchronization completed successfully.</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<e>Error during synchronization: ' . $e->getMessage() . '</e>');
            return Command::FAILURE;
        }
    }
}
