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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Profile implements ActionInterface, HttpGetActionInterface
{
    /**
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        protected PageFactory $resultPageFactory,
        protected RedirectFactory $resultRedirectFactory,
        private readonly Session $customerSession
    ) {
    }

    /**
     * Execute Controller Action
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute(): Page|ResultInterface|ResponseInterface
    {
        if ($this->customerSession->authenticate()) {
            $resultPage = $this->resultPageFactory->create();
            /** @var \Magento\Customer\Block\Account\Navigation $navigationBlock */
            $navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
            $navigationBlock->setActive('psl/customer/profile');

            return $resultPage;
        }

        return $this->resultRedirectFactory->create()->setPath('customer/account/login');
    }
}
