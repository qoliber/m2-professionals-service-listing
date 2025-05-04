<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Plugin\App;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Layout;
use Magento\Framework\View\Result\Page;

class ModifyMetaTags
{
    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        private readonly Request $request,
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Around execute to modify meta robots for PSL products
     *
     * @param ActionInterface $subject
     * @param callable $proceed
     * @return mixed
     */
    public function aroundExecute(
        ActionInterface $subject,
        callable $proceed
    ) {
        /** @var Page|Layout $result */
        $result = $proceed();

        if (!$result instanceof Layout || $this->request->getFullActionName() !== 'catalog_product_view') {
            return $result;
        }

        try {
            $productId = $this->request->getParam('id');
            if (!$productId) {
                return $result;
            }

            $product = $this->productRepository->getById($productId);
            if (str_starts_with($product->getSku(), 'psl_')) {
                // @phpstan-ignore-next-line
                $result->getConfig()->setMetadata(PageConfig::META_ROBOTS, 'NOINDEX,NOFOLLOW');
            }
        } catch (\Exception) {
            return $result;
        }

        return $result;
    }
}
