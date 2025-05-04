<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Block\Customer\Profile;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class PslProfile extends Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param mixed[] $data
     */
    public function __construct(
        Template\Context $context,
        private readonly ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Gets product URL by feature key
     *
     * @param string $featureKey Feature key to get product URL for
     * @return string Product URL or empty string if product not found
     */
    public function getFeatureProductUrl(string $featureKey): string
    {
        try {
            $product = $this->productRepository->get('psl_profile_' . $featureKey);

            return $product->getUrlInStore(); // @phpstan-ignore-line
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}
