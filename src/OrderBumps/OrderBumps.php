<?php

declare(strict_types=1);

namespace IstpaySDK\SDK\OrderBumps;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class OrderBumps extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get($shop_id, array $data = []): Response
    {
        $data['shop_id'] = $shop_id;

        return new Response($this->request('GET','order-bumps', [
            'query' => $data
        ]));
    }
}