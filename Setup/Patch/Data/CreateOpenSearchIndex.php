<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Setup\Patch\Data;

use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class CreateOpenSearchIndex implements DataPatchInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly ClientResolver $clientResolver,
    ) {
    }

    /**
     * Apply Patch
     *
     * @return \Qoliber\Psl\Setup\Patch\Data\CreateOpenSearchIndex
     */
    public function apply(): CreateOpenSearchIndex
    {
        /** @var \Magento\OpenSearch\Model\OpenSearch $client */
        $client = $this->clientResolver->create();

        if ($client->getOpenSearchClient()->indices()->exists(
            ['index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index')]
        )) {
            return $this;
        }

        $client->getOpenSearchClient()->indices()->create([
            'index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index'),
            'body' => [
                'settings' => [
                    "index.max_ngram_diff" => 10,
                    'analysis' => [
                        'tokenizer' => [
                            'ngram_tokenizer' => [
                                'type' => 'ngram',
                                'min_gram' => 2,
                                'max_gram' => 10,
                                'token_chars' => ['letter', 'digit']
                            ]
                        ],
                        'analyzer' => [
                            'ngram_analyzer' => [
                                'type' => 'custom',
                                'tokenizer' => 'ngram_tokenizer',
                                'filter' => ['lowercase', 'asciifolding']
                            ],
                            'value_normalizer' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => ['lowercase', 'asciifolding']
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' => [
                        'customer_id' => ['type' => 'integer'],
                        'status' => ['type' => 'integer'],
                        'account_type' => ['type' => 'keyword'],
                        'company_name' => ['type' => 'text', 'index' => false],
                        'logo' => ['type' => 'keyword', 'index' => false],
                        'homepage_url' => ['type' => 'keyword', 'index' => false],
                        'full_address' => ['type' => 'text', 'index' => false],
                        'location' => [
                            'type' => 'geo_point'
                        ],
                        'short_description' => ['type' => 'text', 'index' => false],
                        'description' => ['type' => 'text', 'index' => false],

                        'country' => [
                            'type' => 'text',
                            'analyzer' => 'value_normalizer'
                        ],
                        'city' => [
                            'type' => 'text',
                            'analyzer' => 'ngram_analyzer',
                            'search_analyzer' => 'value_normalizer'
                        ],
                        'services' => ['type' => 'keyword'],
                        'certificates' => [
                            'type' => 'nested',
                            'properties' => [
                                'certificate_type' => ['type' => 'keyword'],
                                'count' => ['type' => 'integer']
                            ]
                        ],
                        'social_media_links' => [
                            'type' => 'nested',
                            'properties' => [
                                'name' => ['type' => 'keyword'],
                                'url' => ['type' => 'keyword']
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return $this;
    }

    /**
     * Get Dependencies
     *
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get Aliases
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
