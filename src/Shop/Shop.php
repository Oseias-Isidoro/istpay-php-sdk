<?php

declare(strict_types=1);

namespace IstpaySDK\SDK\Shop;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Shop extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function me(): Response
    {
        return new Response($this->request('GET','shop/me'));
    }
}