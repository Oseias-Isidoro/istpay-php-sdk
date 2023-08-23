<?php

namespace IstpaySDK\SDK;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Gateway\Gateway;

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
            json_decode($response->getBody())->data->data[0] : $response->getBody();
    }
}