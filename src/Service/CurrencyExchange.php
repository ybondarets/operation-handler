<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use Exception;

/**
 * Class CurrencyExchange
 *
 * @package App\Service
 */
class CurrencyExchange implements CurrencyExchangeInterface
{
    /** @var HttpClientInterface */
    private HttpClientInterface $client;

    /** @var string */
    private string $apiKey;

    /** @var string */
    private string $baseUrl;

    /**
     * CurrencyExchange constructor.
     *
     * @param string $baseUrl
     * @param string $apiKey
     */
    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->client = $this->createClient();
    }

    /**
     * @param array $currencyCodes
     *
     * @return array
     */
    public function getRates(array $currencyCodes): array
    {
        try {
            $ratesJson = $this
                ->client
                ->request(
                    Request::METHOD_GET,
                    $this->createRequestUrl($currencyCodes)
                )
                ->getContent();

            $rates = json_decode($ratesJson, true);

            return $rates['rates'];
        } catch (Throwable $exception) {
            throw new Exception('Cant get currency rate for ' . join(', ', $currencyCodes), 0, $exception);
        }
    }

    /**
     * @param float  $value
     * @param string $currencyCode
     *
     * @return float
     *
     * @throws Exception
     */
    public function convertToEur(float $value, string $currencyCode): float
    {
        $rates = $this->getRates([$currencyCode]);

        if (!array_key_exists($currencyCode, $rates)) {
            throw new Exception('Cant get currency rate for ' . $currencyCode);
        }

        return $value / $rates[$currencyCode];
    }

    /**
     * @param array $currencyCodes
     *
     * @return string
     */
    private function createRequestUrl(array $currencyCodes): string
    {
        return join('&', [
            $this->baseUrl,
            'access_key=' . $this->apiKey,
            'symbols=' . join(',', $currencyCodes),
        ]);
    }

    /**
     * @return HttpClientInterface
     */
    private function createClient(): HttpClientInterface
    {
        return HttpClient::create();
    }
}
