<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Qoliber\Psl\Service\PslOrderedItemsProvider;

class PslProfileFieldsSubscription implements ArgumentInterface
{
    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Qoliber\Psl\Service\PslOrderedItemsProvider $pslOrderedItemsProvider
     * @param mixed[] $customerPslSections
     */
    public function __construct(
        private readonly Session $customerSession,
        private readonly PslOrderedItemsProvider $pslOrderedItemsProvider,
        private array $customerPslSections = []
    ) {
        $this->customerPslSections = $this->getCustomerPslOrderedItems();
    }

    /**
     * Get customer PSL ordered items with order information
     *
     * @return mixed[]
     */
    public function getCustomerPslOrderedItems(): array
    {
        try {
            return $this->pslOrderedItemsProvider->getCustomerPslOrderedItems(
                (int)$this->customerSession->getCustomerId()
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Has Active Subscription
     *
     * @param string $fields
     * @return bool
     */
    public function hasActiveSubscription(string $fields): bool
    {
        return isset($this->customerPslSections['psl_profile_' . $fields]);
    }

    /**
     * Get subscription end date for given PSL feature
     *
     * @param string $fields Feature identifier (logo, profile_page, etc.)
     * @return string
     */
    public function getSubscriptionEndDate(string $fields): string
    {
        $sku = 'psl_profile_' . $fields;

        if (!isset($this->customerPslSections[$sku])) {
            return '';
        }

        $date = $this->customerPslSections[$sku];
        if (!$date) {
            return '';
        }

        return $date;
    }
}
