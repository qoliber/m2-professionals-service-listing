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

class AccountTypes implements ArgumentInterface
{
    /**
     * @param \Qoliber\Psl\Model\Config\Source\AccountTypes $accountTypes
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        private readonly \Qoliber\Psl\Model\Config\Source\AccountTypes $accountTypes,
        private readonly Session $customerSession
    ) {
    }

    /**
     * Get Account Types
     *
     * @return mixed[]
     */
    public function getAccountTypes(): array
    {
        return $this->accountTypes->toOptionArray();
    }

    /**
     * Get Customer Account Type
     *
     * @return int
     */
    public function getCustomerAccountType(): int
    {
        return (int) $this->customerSession->getCustomer()->getData('account_type');
    }

    /**
     * Is Customer Profile Public
     *
     * @return bool
     */
    public function isCustomerPublicProfile(): bool
    {
        return (bool) $this->customerSession->getCustomer()->getData('public_profile');
    }
}
