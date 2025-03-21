<?php

declare(strict_types=1);

namespace Qoliber\Psl\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\Data\ProfileInterfaceFactory;

class Profile extends AbstractModel
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Qoliber\Psl\Api\Data\ProfileInterfaceFactory $profileDataFactory
     * @param \Qoliber\Psl\Model\ResourceModel\Profile $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly ProfileInterfaceFactory $profileDataFactory,
        ResourceModel\Profile $resource,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Get Data Model
     *
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function getDataModel(): ProfileInterface
    {
        $data = $this->getData();

        $dataObject = $this->profileDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dataObject,
            $data,
            ProfileInterfaceFactory::class
        );

        return $dataObject;
    }
}
