<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class PslProducts implements OptionSourceInterface
{
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory
    ) {
    }

    /**
     * Get an option array for system configuration
     *
     * @return mixed[]
     */
    public function toOptionArray(): array
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'sku'])
            ->addAttributeToFilter('sku', ['like' => 'psl_%'])
            ->addAttributeToFilter('status', ['eq' => 1]);

        $options = [['value' => '', 'label' => __('-- Please Select --')]];

        foreach ($collection as $product) {
            $options[] = [
                'value' => $product->getSku(),
                'label' => sprintf('%s (%s)', $product->getName(), $product->getSku())
            ];
        }

        return $options;
    }
}
