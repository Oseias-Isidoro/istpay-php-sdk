<?php

declare(strict_types=1);

namespace IstpaySDK\SDK\Integrations;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Request;
use IstpaySDK\SDK\Response;

class Integrations extends Request
{
    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function woo_integration(array $data): Response
    {
        return new Response($this->request('POST','integrations/woo_integration', [
            'form_params' => [
                'params' => $data
            ]
        ]));
    }
}