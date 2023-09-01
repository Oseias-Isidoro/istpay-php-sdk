<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\Pixels;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Pixels extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get($shop_id): Response
    {
        return new Response($this->request('GET','pixels', [
            'query' => [ 'shop_id' => $shop_id ]
        ]));
    }
}