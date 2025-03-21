<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Plugin\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Qoliber\Psl\Model\Data\ProfileFactory;

class PslProfile
{
    /**
     * @param \Qoliber\Psl\Model\Data\ProfileFactory $profileFactory
     */
    public function __construct(
        protected ProfileFactory $profileFactory,
    ) {
    }

    /**
     * After Get Plugin
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function afterGet(CustomerRepositoryInterface $subject, CustomerInterface $customer): CustomerInterface
    {
        $extensionAttributes = $customer->getExtensionAttributes();

        if (!$extensionAttributes) {
            return $customer;
        }

        $pslProfile = $this->profileFactory->create();
        $extensionAttributes->setPslProfile($pslProfile);

        return $customer;
    }

    /**
     * After Get By Id Plugin
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $customer): CustomerInterface
    {
        $extensionAttributes = $customer->getExtensionAttributes();

        if (!$extensionAttributes) {
            return $customer;
        }

        $pslProfile = $this->profileFactory->create();
        $extensionAttributes->setPslProfile($pslProfile);

        return $customer;
    }
}
