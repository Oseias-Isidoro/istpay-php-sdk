<?php

declare(strict_types=1);


namespace IstpaySDK\SDK\Products;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Response;

class Products extends \IstpaySDK\SDK\Request
{
    const TYPE_DIGITAL = 'digital';
    const TYPE_PHYSICAL = 'physical';

    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function get(array $options = []): Response
    {
        return new Response($this->request('GET','products', [
            'query' => $options
        ]));
    }

    /**
     * @throws GuzzleException
     */
    public function create(array $data): Response
    {
        return new Response($this->request('POST','products', [
            'form_params' => $data
        ]));
    }

    /**
     * @throws GuzzleException
     */
    public function update($product_id, array $data): Response
    {
        return new Response($this->request('PUT',"products/$product_id", [
            'form_params' => $data
        ]));
    }

    /**
     * @throws GuzzleException
     */
    public function delete($product_id): Response
    {
        return new Response($this->request('DELETE',"products/$product_id"));
    }
}