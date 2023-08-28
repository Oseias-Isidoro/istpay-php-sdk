<?php

declare(strict_types=1);

namespace IstpaySDK\SDK;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Gateway\Gateway;
use IstpaySDK\SDK\Withdraw\Withdraw;

class Istpay extends Request
{
    const ENVIRONMENT_PROD = 'prod';
    const ENVIRONMENT_HML = 'hml';

    public function __construct(string $token, string $environment = self::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    public function gateway(): Gateway
    {
        return new Gateway($this->getToken(), $this->getEnvironment());
    }

    public function withdraw(): Withdraw
    {
        return new Withdraw($this->getToken(), $this->getEnvironment());
    }

    /**
     * @throws GuzzleException
     */
    public function getOrder($orderID)
    {
        $response = $this->request('GET', 'orders', [
            'query' => [
                'id' => $orderID
            ]
        ]);

        return $response->getStatusCode() == 200 ?
            json_decode((string) $response->getBody())->data->data[0] : $response->getBody();
    }
}