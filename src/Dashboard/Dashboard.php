<?php

declare(strict_types=1);

namespace IstpaySDK\SDK\Dashboard;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Dashboard extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $token, string $period_start, string $period_end): Response
    {
        return new Response($this->request('POST','dashboard/infos', [
            'form_params' => ['token' => $token],
            'query' => [
                'period' => "$period_start - $period_end"
            ]
        ]));
    }
}