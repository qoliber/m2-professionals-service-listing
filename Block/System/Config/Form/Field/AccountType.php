<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class AccountType extends AbstractFieldArray
{
    /**
     * Construct implementation
     *
     * @return void
     */
    protected function _construct() : void
    {
        $this->addColumn(
            'account_type',
            [
                'label' => __('Account Type')->render()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Account Type')->render();

        parent::_construct();
    }
}
