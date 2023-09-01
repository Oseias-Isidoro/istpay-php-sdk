<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\Notifications;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Notifications extends \IstpaySDK\SDK\Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function list($token): Response
    {
        return new Response($this->request('POST','notification/list', [
            'form_params' => [
                'token' => $token
            ]
        ]));
    }
}