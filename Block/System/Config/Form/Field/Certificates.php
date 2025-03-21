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

class Certificates extends AbstractFieldArray
{
    /**
     * Construct implementation
     *
     * @return void
     */
    protected function _construct() : void
    {
        $this->addColumn(
            'certificate',
            [
                'label' => __('Certificate')->render()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Certificate')->render();

        parent::_construct();
    }
}
