<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\Data\ProfileSearchResultsInterface;

interface ProfileRepositoryInterface
{
    /**
     * Get by Id
     *
     * @param int $id
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function get(int $id): ProfileInterface;

    /**
     * Get By Customer Id
     *
     * @param int $customerId
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function getByCustomerId(int $customerId): ProfileInterface;

    /**
     * Get List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Qoliber\Psl\Api\Data\ProfileSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ProfileSearchResultsInterface;

    /**
     * Save
     *
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $entity
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function save(ProfileInterface $entity): ProfileInterface;

    /**
     * Delete by Entity
     *
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $entity
     * @return bool
     */
    public function delete(ProfileInterface $entity): bool;

    /**
     * Delete By Id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
