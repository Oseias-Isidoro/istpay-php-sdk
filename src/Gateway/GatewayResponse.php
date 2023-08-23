<?php

namespace IstpaySDK\SDK\Gateway;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GatewayResponse
{
    private ResponseInterface $response;
    private int $http_code;
    private array $errors;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->http_code = $response->getStatusCode();
    }

    public function getRawResponse(): StreamInterface
    {
        return $this->response->getBody();
    }

    public function success(): bool
    {
        $response_decoded = $this->responseToArray();
        return ($this->http_code === 200 and $response_decoded['status'] == Gateway::PAYMENT_SUCCESS);
    }

    public function responseToArray()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function paymentCode()
    {
        if ($this->success())
            return $this->responseToArray()['payment']['payment_code'];

        return null;
    }

    public function transactionID()
    {
        if ($this->success())
                return $this->responseToArray()['payment']['transaction_id'];

        return null;
    }

    public function boletoPDFLink()
    {
        if ($this->success())
            return $this->responseToArray()['payment']['link'];

        return null;
    }

    public function boletoDueDate()
    {
        if ($this->success())
            return $this->responseToArray()['payment']['due_date'];

        return null;
    }

    public function paymentStatus()
    {
        if ($this->success())
            return $this->responseToArray()['payment']['status'];

        return null;
    }

    public function orderID()
    {
        if ($this->http_code == 200)
            return $this->responseToArray()['order_id'];

        return null;
    }

    public function getErrors(): array
    {
        $response_decoded = $this->responseToArray();

        if ($this->http_code == 422)
        {
            foreach ($response_decoded['errors'] as $value)
            {
                $this->addMsgError($value[0]);
            }
        } else if ($this->http_code === 200) {
            if ($response_decoded['status'] == Gateway::PAYMENT_ERROR)
                $this->addMsgError($response_decoded['message']);
        } else {
            $this->addMsgError($this->getRawResponse());
        }

        return $this->errors;
    }

    private function addMsgError(string $msg)
    {
        $this->errors[] = $msg;
    }
}