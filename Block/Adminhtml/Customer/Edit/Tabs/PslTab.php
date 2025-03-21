<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Block\Adminhtml\Customer\Edit\Tabs;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class PslTab extends Template implements TabInterface
{
    /** @var string */
    protected $_template = 'customer/profile.phtml';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected Context $context,
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
        parent::__construct($context);
    }

    /**
     * Get Tab Label
     *
     * @return string
     */
    public function getTabLabel(): string
    {
        return __('PSL Profile')->render();
    }

    /**
     * Get Tab Title
     *
     * @return string
     */
    public function getTabTitle(): string
    {
        return __('PSL Profile')->render();
    }

    /**
     * Can Show Tab
     *
     * @return bool
     */
    public function canShowTab(): bool
    {
        return $this->isModuleEnabled();
    }

    /**
     * Is Hidden Tab
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return !$this->isModuleEnabled();
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * Get Tab Class
     *
     * @return string
     */
    public function getTabClass(): string
    {
        return '';
    }

    /**
     * Get Tab Url
     *
     * @return string
     */
    public function getTabUrl(): string
    {
        return '';
    }

    /**
     * Check if Module is Enabled
     *
     * @return bool
     */
    private function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag('qoliber_psl/general/enabled');
    }
}
