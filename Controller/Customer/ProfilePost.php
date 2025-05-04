<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Controller\Customer;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Url;
use Magento\Framework\Filesystem;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\ProfileRepositoryInterface;
use Qoliber\Psl\Service\PslNotificationService;

class ProfilePost implements HttpPostActionInterface
{
    /**
     * @param \Magento\Framework\Url $url
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Qoliber\Psl\Api\ProfileRepositoryInterface $profileRepository
     * @param \Magento\Framework\File\UploaderFactory $uploaderFactory
     * @param \Qoliber\Psl\Service\PslNotificationService $notificationService
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        protected Url $url,
        protected ResponseInterface $response,
        protected ManagerInterface $messageManager,
        protected ProfileRepositoryInterface $profileRepository,
        protected UploaderFactory $uploaderFactory,
        protected PslNotificationService $notificationService,
        private readonly Filesystem $filesystem,
        private readonly RequestInterface $request,
        private readonly Session $customerSession,
    ) {
    }

    /**
     * Execute Controller Action
     *
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute(): ResponseInterface
    {
        // @phpstan-ignore-next-line
        $this->response->setRedirect($this->url->getUrl('customer/account/login'));

        if ($this->customerSession->authenticate()) {
            // @phpstan-ignore-next-line
            $this->response->setRedirect($this->url->getUrl('psl/customer/profile/'));

            try {
                $this->updateCustomerProfile(
                    $this->getCustomerId(),
                    $this->request->getParams(),
                );
                $this->messageManager->addSuccessMessage(__('Your profile has been updated.')->render());
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(
                    __('There was an error saving your profile: %1.', $e->getMessage())->render()
                );
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(
                    __('There was an error saving your profile: %1.', $e->getMessage())->render()
                );
            }
        }

        return $this->response;
    }

    /**
     * Get Customer Id
     *
     * @return int
     */
    private function getCustomerId(): int
    {
        return (int) $this->customerSession->getCustomerId();
    }

    /**
     * Validate Image
     *
     * @return string|null
     * @throws \Exception
     */
    private function processImage(): ?string
    {
        // @phpstan-ignore-next-line
        $profilePicture = $this->request->getFiles()['logo'] ?? null;

        if (isset($profilePicture) && $profilePicture['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = $this->uploaderFactory->create(['fileId' => 'logo']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg', 'webp']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('psl');
            $uploader->save($path);

            return sprintf('psl/%s', $uploader->getUploadedFileName());
        }

        return null;
    }

    /**
     * Update Customer Profile
     *
     * @param int $customerId
     * @param mixed[] $params
     * @return void
     * @throws \Exception
     */
    private function updateCustomerProfile(int $customerId, array $params): void
    {
        $imagePath = $this->processImage();
        unset($params['status']); // just in case some joker forces their status ;-)

        $customerProfile = $this->profileRepository->getByCustomerId($customerId);

        if ($customerProfile->getProfileId() === null) {
            $customerProfile->setCustomerId($customerId);
        }

        $params['social_media_links'] = json_encode($params['social_media_links'] ?? []);
        $params['services'] = json_encode($params['services'] ?? []);
        $params['certificates'] = json_encode($params['certificates'] ?? []);
        $params['short_description'] = strip_tags($params['short_description']);

        if (!empty($params['remove_logo'])) {
            $params['logo'] = "";
        } else {
            if ($imagePath) {
                $params['logo'] = $imagePath;
            }
        }

        if ($customerProfile->getStatus() === 0) {
            $customerProfile->setStatus(ProfileInterface::STATUS_PENDING);
            $this->notificationService->sendNotification(
                [
                    'customer' => $this->customerSession->getCustomer(),
                ]
            );
        }

        // @phpstan-ignore-next-line
        $customerProfile->addData($params);
        $this->profileRepository->save($customerProfile);
    }
}
