<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Message\ManagerInterface;
use Qoliber\Psl\Api\ProfileRepositoryInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Service\PslNotificationService;

class ProcessProfilePost extends Action
{
    /** @var string  */
    public const ADMIN_RESOURCE = 'Qoliber_Psl::config';

    /**+
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Qoliber\Psl\Api\ProfileRepositoryInterface $profileRepository
     * @param \Qoliber\Psl\Service\PslNotificationService $notificationService
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        protected Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly PslNotificationService $notificationService,
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {
        parent::__construct($context);
    }

    /**
     * Process profile action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute(): \Magento\Framework\Controller\Result\Json
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $profileId = (int) $this->getRequest()->getParam('profile_id');
            $action = $this->getRequest()->getParam('action');
            $customerId = $this->getRequest()->getParam('customer_id');
            $customerEmail = $this->customerRepository->getById($customerId)->getEmail();
            $rejectionReason = $this->getRequest()->getParam('rejection_reason');

            if (!$profileId || !$action) {
                throw new \InvalidArgumentException(__('Missing required parameters')->render());
            }

            $profile = $this->profileRepository->get($profileId);

            switch ($action) {
                case 'approved':
                    $profile->setStatus(ProfileInterface::STATUS_APPROVED);
                    $message = __('Profile has been approved successfully.');
                    break;
                case 'disapproved':
                    if (empty($rejectionReason)) {
                        throw new \InvalidArgumentException(__('Rejection reason is required')->render());
                    }
                    $profile->setStatus(ProfileInterface::STATUS_REJECTED);
                    $message = __('Profile has been rejected. Partner has been notified.');
                    break;
                default:
                    throw new \InvalidArgumentException(__('Invalid action specified')->render());
            }

            $this->profileRepository->save($profile);
            $this->notificationService->sendProfileNotification(
                $customerEmail,
                [
                    'profile' => $profile,
                    'status' => $action,
                    'message' => $rejectionReason ?? null
                ]
            );

            return $resultJson->setData([
                'profileStatus' => $profile->getProfileStatusLabel(),
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
