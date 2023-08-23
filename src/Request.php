<?php

namespace IstpaySDK\SDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Request
{
    const HOST_HML = 'https://homologationcheckout.istpay.com.br/api/';
    const HOST_PROD = 'https://checkout.istpay.com.br/api/';

    private string $token;
    private string $environment;

    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        $this->token = $token;
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * @throws GuzzleException
     */
    protected function request(string $method, string $uri, array $data): ResponseInterface
    {
        $client = new Client([
            'base_uri' => $this->getHost(),
            'verify' => false,
            'http_errors' => false
        ]);

        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Accept'        => 'application/json',
        ];

        return $client->request($method, $uri, [
            'headers' => $headers,
            ...$data
        ]);
    }

    private function getHost(): string
    {
        if ($this->environment == Istpay::ENVIRONMENT_PROD)
            return self::HOST_PROD;

        return self::HOST_HML;
    }
}