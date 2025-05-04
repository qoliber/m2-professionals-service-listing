<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Plugin;

use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\ProfileRepositoryInterface;
use Qoliber\Psl\Service\OpenSearchProfileDataFormatter;

class OpenSearchProfileSync
{
    /**
     * @param \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver
     * @param \Qoliber\Psl\Service\OpenSearchProfileDataFormatter $profileDataFormatter
     */
    public function __construct(
        private readonly ClientResolver $clientResolver,
        private readonly OpenSearchProfileDataFormatter $profileDataFormatter
    ) {
    }

    /**
     * After Plugin for ProfileRepositoryInterface::save
     *
     * @param \Qoliber\Psl\Api\ProfileRepositoryInterface $subject
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $profile
     *
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterSave(ProfileRepositoryInterface $subject, ProfileInterface $profile): ProfileInterface
    {
        try {
            /** @var \Magento\OpenSearch\Model\OpenSearch $openSearch */
            $openSearch = $this->clientResolver->create();
            $indexData = $this->profileDataFormatter->format($profile);

            $openSearch->getOpenSearchClient()->index([
                'index' => $indexData['index'],
                'id' => $indexData['id'],
                'body' => $indexData['body']
            ]);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Failed to sync profile to OpenSearch: %1', $e->getMessage()));
        }

        return $profile;
    }
}
