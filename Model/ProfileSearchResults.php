<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model;

use Magento\Framework\Api\SearchResults;
use Qoliber\Psl\Api\Data\ProfileSearchResultsInterface;

class ProfileSearchResults extends SearchResults implements ProfileSearchResultsInterface
{

}
