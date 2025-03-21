<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Service;

use Magento\Customer\Model\Customer;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class PslNotificationService
{
    /**
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly TransportBuilder $transportBuilder,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    /**
     * Send Notification / emails
     *
     * @param mixed[] $templateVars
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendNotification(array $templateVars): void
    {
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->getEmailTemplateNotificationForAdmin())
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFromByScope($this->scopeConfig->getValue(Customer::XML_PATH_REGISTER_EMAIL_IDENTITY))
            ->addTo($this->scopeConfig->getValue('trans_email/ident_support/email'))
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Send profile status notification
     *
     * @param string $customerEmail
     * @param mixed[] $templateVars
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendProfileNotification(string $customerEmail, array $templateVars): void
    {
        $status = $templateVars['status'];
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->getEmailTemplateProfileNotification($status))
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $this->storeManager->getStore()->getId(),
            ])
            ->setTemplateVars($templateVars)
            ->setFromByScope($this->scopeConfig->getValue(Customer::XML_PATH_REGISTER_EMAIL_IDENTITY))
            ->addTo($customerEmail)
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Get General Notification Template
     *
     * @return string
     */
    private function getEmailTemplateNotificationForAdmin(): string
    {
        return $this->scopeConfig->getValue(
            'qoliber_psl/emails/profile_notification',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Customer Notification Template
     *
     * @param string $status
     * @return string
     */
    private function getEmailTemplateProfileNotification(string $status): string
    {
        return $this->scopeConfig->getValue(
            sprintf('qoliber_psl/emails/profile_%s', $status),
            ScopeInterface::SCOPE_STORE
        );
    }
}
