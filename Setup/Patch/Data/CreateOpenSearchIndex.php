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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class CreateOpenSearchIndex implements DataPatchInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \OpenSearch\Client|null $client
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private ?Client $client = null
    ) {
        $this->client = ClientBuilder::fromConfig(
            $this->buildOSConfig(
                $this->scopeConfig->getValue('catalog/search/opensearch_server_hostname'),
                $this->scopeConfig->getValue('catalog/search/opensearch_server_port'),
                $this->scopeConfig->getValue('catalog/search/opensearch_username') ?: '',
                $this->scopeConfig->getValue('catalog/search/opensearch_password') ?: '',
                (bool) $this->scopeConfig->getValue('catalog/search/opensearch_enable_auth')
            ),
            true
        );
    }

    /**
     * Build Config
     *
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     * @param bool $auth
     * @return mixed[]
     */
    private function buildOSConfig(string $host, string $port, string $user, string $password, bool $auth): array
    {
        $hostname = preg_replace('/http[s]?:\/\//i', '', $host);
        // @codingStandardsIgnoreStart
        $protocol = parse_url($host, PHP_URL_SCHEME);
        // @codingStandardsIgnoreEnd
        if (!$protocol) {
            $protocol = 'http';
        }

        $authString = '';

        if ($auth) {
            $authString = "{$user}:{$password}@";
        }

        $portString = '';
        if (!empty($port)) {
            $portString = ':' . $port;
        }

        $host = $protocol . '://' . $authString . $hostname . $portString;

        return [
            'hosts' => [$host],
            'hostname' => $hostname,
            'port' => $port,
            'enableAuth' => $auth,
            'username' => $user,
            'password' => $password,
            'timeout' => 15,
            'retries' => 3
        ];
    }

    /**
     * Apply Patch
     *
     * @return \Qoliber\Psl\Setup\Patch\Data\CreateOpenSearchIndex
     */
    public function apply(): CreateOpenSearchIndex
    {
        if ($this->client) {
            if ($this->client->indices()->exists(
                ['index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index')]
            )) {
                return $this;
            }

            $this->client->indices()->create([
                'index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index'),
                'body' => [
                    'settings' => [
                        "index.max_ngram_diff" => 10,
                        'analysis' => [
                            'analyzer' => [
                                'tokenizer' => [
                                    'ngram_tokenizer' => [
                                        'type' => 'ngram',
                                        'min_gram' => 2,
                                        'max_gram' => 10,
                                        'token_chars' => ['letter', 'digit']
                                    ]
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
        }

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
