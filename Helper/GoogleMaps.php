<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

class GoogleMaps extends AbstractHelper
{
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
