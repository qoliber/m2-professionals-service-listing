<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\Data\ProfileInterfaceFactory;
use Qoliber\Psl\Api\Data\ProfileSearchResultsInterface;
use Qoliber\Psl\Api\Data\ProfileSearchResultsInterfaceFactory;
use Qoliber\Psl\Api\ProfileRepositoryInterface;
use Qoliber\Psl\Model\ResourceModel\Profile as ResourceProfile;
use Qoliber\Psl\Model\ResourceModel\Profile\CollectionFactory as ProfileCollectionFactory;

class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * @param \Qoliber\Psl\Model\ResourceModel\Profile $resource
     * @param \Qoliber\Psl\Model\ProfileFactory $profileFactory
     * @param \Qoliber\Psl\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory
     * @param \Qoliber\Psl\Api\Data\ProfileSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        private readonly ResourceProfile $resource,
        private readonly ProfileFactory $profileFactory,
        private readonly ProfileCollectionFactory $profileCollectionFactory,
        private readonly ProfileSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor,
        private readonly ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
    }

    /**
     * Save
     *
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $entity
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ProfileInterface $entity): ProfileInterface
    {
        $profileData = $this->extensibleDataObjectConverter->toNestedArray(
            $entity,
            [],
            ProfileInterface::class
        );

        $profileModel = $this->profileFactory->create()->setData($profileData);

        try {
            $this->resource->save($profileModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the profile: %1',
                $exception->getMessage()
            ));
        }

        return $profileModel->getDataModel();
    }

    /**
     * Get By Id
     *
     * @param int $id
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id): ProfileInterface
    {
        $profile = $this->profileFactory->create();
        $this->resource->load($profile, $id);
        if (!$profile->getId()) {
            throw new NoSuchEntityException(__('Profile with id "%1" does not exist.', $id));
        }

        return $profile->getDataModel();
    }

    /**
     * Get By Customer Id
     *
     * @param int $customerId
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function getByCustomerId(int $customerId): ProfileInterface
    {
        $collection = $this->profileCollectionFactory->create();
        $collection->addFieldToFilter('customer_id', (string) $customerId);

        return $collection->getFirstItem()->getDataModel();
    }

    /**
     * Get List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Qoliber\Psl\Api\Data\ProfileSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ProfileSearchResultsInterface
    {
        $collection = $this->profileCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            ProfileInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];

        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete By Id
     *
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $entity
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ProfileInterface $entity): bool
    {
        try {
            $profileModel = $this->profileFactory->create();
            $this->resource->load($profileModel, $entity->getProfileId());
            $this->resource->delete($profileModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Profile: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * Delete By Id
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->get($id));
    }
}
