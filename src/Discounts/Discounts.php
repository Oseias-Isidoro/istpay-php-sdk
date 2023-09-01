<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\Discounts;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Discounts extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get($shop_id, array $options = []): Response
    {
        return new Response($this->request('GET','discounts', [
            'query' => [
                'shop_id' => $shop_id,
                ...$options
            ]
        ]));
    }

    /**
     * @throws GuzzleException
     */
    public function validateCoupon($shop_id, array $options = []): Response
    {
        return new Response($this->request('POST','discounts', [
            'form_params' => [
                'shop_id' => $shop_id,
                ...$options
            ]
        ]));
    }
}