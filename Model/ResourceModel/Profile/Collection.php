<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model\ResourceModel\Profile;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Qoliber\Psl\Model\Profile;

class Collection extends AbstractCollection
{
    /**
     * _Construct Implementation
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Profile::class, \Qoliber\Psl\Model\ResourceModel\Profile::class);
    }
}
