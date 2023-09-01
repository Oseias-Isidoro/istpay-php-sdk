<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\AbandonedCart;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class AbandonedCard extends \IstpaySDK\SDK\Request
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
        return new Response($this->request('GET','abandoned-carts', [
            'query' => [
                ...$options
            ]
        ]));
    }
}