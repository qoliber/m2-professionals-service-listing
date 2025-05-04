<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProfileSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Items
     *
     * @return \Qoliber\Psl\Api\Data\ProfileInterface[]
     */
    public function getItems(): array;

    /**
     * Set Items
     *
     * @param \Qoliber\Psl\Api\Data\ProfileInterface[] $items
     */
    public function setItems(array $items);
}
