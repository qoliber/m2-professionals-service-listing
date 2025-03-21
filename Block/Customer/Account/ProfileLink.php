<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Block\Customer\Account;

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\View\Element\Template\Context;
use Qoliber\Psl\Model\Config\Source\AccountTypes;

class ProfileLink extends Link\Current implements SortLinkInterface
{
    /**
     * @param \Qoliber\Psl\Model\Config\Source\AccountTypes $accountTypes
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param mixed[] $data
     */
    public function __construct(
        private readonly AccountTypes $accountTypes,
        private readonly Session $customerSession,
        protected Context $context,
        protected DefaultPathInterface $defaultPath,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
    }

    /**
     * Get Label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(): Phrase
    {
        return __('My %1 Profile', $this->getAccountLabel());
    }

    /**
     * Get Account Type
     *
     * @return int
     */
    private function getAccountType(): int
    {
        return (int) $this->customerSession->getCustomer()->getData('account_type');
    }

    /**
     * Get Account Label
     *
     * @return string
     */
    private function getAccountLabel(): string
    {
        $accountId = $this->getAccountType();
        $accountTypes = $this->accountTypes->toArray();

        return $accountTypes[$accountId];
    }

    /**
     * Get Sort Order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int) $this->getData('sort_order');
    }
}
