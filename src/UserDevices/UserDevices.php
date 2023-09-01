<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\UserDevices;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class UserDevices extends \IstpaySDK\SDK\Request
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
        return new Response($this->request('POST','user-devices/list', [
            'form_params' => [
                'token' => $token
            ]
        ]));
    }

    /**
     * @throws GuzzleException
     */
    public function store(string $token, string $device_id): Response
    {
        return new Response($this->request('POST','user-devices/store', [
            'form_params' => [
                'token' => $token,
                'device_id' => $device_id,
            ]
        ]));
    }
}