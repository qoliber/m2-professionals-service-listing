<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Block\Adminhtml\Customer;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template\File\Validator;
use Qoliber\Psl\ViewModel\Details;

class Profile extends Template
{
    /**
     * @param Context $context
     * @param Details $detailsViewModel
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        private readonly Details $detailsViewModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Details View Model
     *
     * @return Details
     */
    public function getDetailsViewModel(): Details
    {
        return $this->detailsViewModel;
    }

    /**
     * Check if tab content should be loaded via Ajax
     *
     * @return bool
     */
    public function isAjaxLoaded(): bool
    {
        return true;
    }
}
