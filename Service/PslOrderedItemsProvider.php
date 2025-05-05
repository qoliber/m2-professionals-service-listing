<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Service;

use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class PslOrderedItemsProvider
{
    /**
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {
    }

    /**
     * Get PSL-ordered items with subscription information for a customer
     *
     * @param int $customerId
     * @return mixed[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerPslOrderedItems(int $customerId): array
    {
        $connection = $this->resourceConnection->getConnection();
        $items = [];

        // Verify customer exists
        $this->customerRepository->getById($customerId);

        $select = $connection->select()
            ->from(
                ['soi' => $this->resourceConnection->getTableName('sales_order_item')],
                ['item_id', 'sku', 'name', 'created_at', 'updated_at']
            )
            ->join(
                ['so' => $this->resourceConnection->getTableName('sales_order')],
                'soi.order_id = so.entity_id',
                ['order_id' => 'entity_id', 'status', 'updated_at']
            )
            ->join(
                ['po' => $this->resourceConnection->getTableName('aw_sarp2_profile_order')],
                'po.order_id = so.entity_id',
                ['profile_id']
            )
            ->where('so.customer_id = ?', $customerId)
            ->where('soi.sku LIKE ?', 'psl_profile_%')
            ->where('so.state = ?', 'complete')
            ->order('so.updated_at DESC');

        foreach ($connection->fetchAll($select) as $orderItem) {
            if (isset($items[$orderItem['sku']])) {
                continue;
            }

            if (isset($orderItem['profile_id'])) {
                $profileId = $orderItem['profile_id'];

                if ($profileId) {
                    $paymentDate = $this->profileManagement->getNextPaymentInfo($profileId)->getPaymentDate();
                    $items[$orderItem['sku']] = $paymentDate;
                }
            }
        }

        return $items;
    }
}
