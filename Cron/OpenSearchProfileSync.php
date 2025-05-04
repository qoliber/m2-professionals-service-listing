<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Cron;

use Qoliber\Psl\Service\OpenSearchProfileSync as SyncService;

class OpenSearchProfileSync
{
    /**
     * @param \Qoliber\Psl\Service\OpenSearchProfileSync $syncService
     */
    public function __construct(
        private readonly SyncService $syncService
    ) {
    }

    /**
     * Execute the cron job
     *
     * @return void
     */
    public function execute(): void
    {
        $this->syncService->execute();
    }
}
