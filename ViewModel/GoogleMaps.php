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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class GoogleMaps implements ArgumentInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    /**
     * Get Google Maps API Key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->scopeConfig->getValue('qoliber_psl/google_maps/api_key') ?? '';
    }
}
