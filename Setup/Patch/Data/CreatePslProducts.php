<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\State;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreatePslProducts implements DataPatchInterface
{
    /** @var mixed[] */
    private const PRODUCTS = [
        'psl_profile_logo' => 'Professional Service Listing - Profile Logo',
        'psl_profile_backlink' => 'Professional Service Listing Profile - Backlink',
        'psl_profile_geolocation' => 'Professional Service Listing - Profile Geolocation',
        'psl_profile_content' => 'Professional Service Listing Profile - Content',
        'psl_profile_social' => 'Professional Service Listing Profile - Social Links',
        'psl_profile_certificates' => 'Professional Service Listing Profile - Certificates',
        'psl_profile_services' => 'Professional Service Listing Profile - Services',
    ];

    /**
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        private readonly State $state,
        private readonly ProductInterfaceFactory $productFactory,
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Applies data patch to create PSL virtual products
     *
     * @return \Magento\Framework\Setup\Patch\DataPatchInterface
     * @throws \Exception
     */
    public function apply(): DataPatchInterface
    {
        $this->state->emulateAreaCode('adminhtml', function () {
            foreach (self::PRODUCTS as $sku => $name) {
                $this->createProduct($sku, $name);
            }
        });

        return $this;
    }

    /**
     * Creates a virtual product for PSL feature
     *
     * @param string $sku Product SKU
     * @param string $name Product name
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function createProduct(string $sku, string $name): void
    {
        try {
            $product = $this->productFactory->create();
            // @phpstan-ignore-next-line
            $product->setTypeId(Type::TYPE_VIRTUAL)
                ->setAttributeSetId($product->getDefaultAttributeSetId()) // @phpstan-ignore-line
                ->setWebsiteIds([1])
                ->setName($name)
                ->setTagline($name)
                ->setSku($sku)
                ->setUrlKey(str_replace('_', '-', $sku))
                ->setVisibility(Visibility::VISIBILITY_IN_CATALOG)
                ->setStatus(Status::STATUS_ENABLED)
                ->setPrice(5.00)
                ->setStockData([
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 0,
                    'is_in_stock' => 1,
                ]);

            $this->productRepository->save($product);
        } catch (\Exception $e) {
            var_dump(sprintf('Could not save product: %s', $e->getMessage()));
        }
    }

    /**
     * Returns an array of patches that have to be executed prior to this
     *
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Returns aliases for the patch
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
