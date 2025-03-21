<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Serialize\SerializerInterface;

class AccountTypes extends AbstractSource implements OptionSourceInterface
{
    /** @var string */
    public const XPATH_PSL_ACCOUNT_TYPES = 'qoliber_psl/settings/account_types';

    /**
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param mixed[] $accountTypes
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ScopeConfigInterface $scopeConfig,
        private array $accountTypes = []
    ) {
    }

    /**
     * To Option Array
     *
     * @return mixed[]
     */
    public function toOptionArray(): array
    {
        return array_map(
            fn($index, $label) => ['value' => $index, 'label' => $label],
            array_keys($this->getAccountTypes()),
            $this->getAccountTypes()
        );
    }

    /**
     * To Array
     *
     * @return array<int, string>
     */
    public function toArray(): array
    {
        return $this->getAccountTypes();
    }

    /**
     * Get All Options
     *
     * @return string[]
     */
    public function getAllOptions(): array
    {
        return $this->toOptionArray();
    }

    /**
     * Get Account Types
     *
     * @return string[]
     */
    private function getAccountTypes(): array
    {
        if (empty($this->accountTypes)) {
            try {
                $decodedData = $this->serializer->unserialize(
                    $this->scopeConfig->getValue(self::XPATH_PSL_ACCOUNT_TYPES)
                );

                if (is_array($decodedData)) {
                    $this->accountTypes = array_values(array_map(fn($item) => $item['account_type'], $decodedData));
                }

            } catch (\InvalidArgumentException $e) {
                $this->accountTypes = [];
            }
        }

        return $this->accountTypes;
    }
}
