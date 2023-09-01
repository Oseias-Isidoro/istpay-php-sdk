<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\Orders;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Orders extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get(array $options = []): Response
    {
        return new Response($this->request('GET','orders', [
            'query' => [
                ...$options
            ]
        ]));
    }
}