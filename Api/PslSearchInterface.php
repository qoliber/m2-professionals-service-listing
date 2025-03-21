<?php

declare(strict_types=1);

namespace Qoliber\Psl\Api;

use Qoliber\Psl\Api\Data\OpenSearchQueryInterface;

interface PslSearchInterface
{
    /**
     * Search PSL profiles using OpenSearch
     *
     * @param \Qoliber\Psl\Api\Data\OpenSearchQueryInterface $filters
     * @return mixed[]
     */
    public function search(OpenSearchQueryInterface $filters): array;
}
