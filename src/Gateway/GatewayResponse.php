<?php

namespace IstpaySDK\SDK\Gateway;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GatewayResponse
{
    private ResponseInterface $response;
    private int $http_code;
    private array $errors = [];

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
        return ($this->http_code === 200 and $this->responseToArray()['status'] == Gateway::PAYMENT_SUCCESS);
    }

    public function responseToArray()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function paymentCode()
    {
        return $this->success() ? $this->responseToArray()['payment']['payment_code'] : null;
    }

    public function transactionID()
    {
        return $this->success() ? $this->responseToArray()['payment']['transaction_id'] : null;
    }

    public function boletoPDFLink()
    {
        return $this->success() ? $this->responseToArray()['payment']['link'] : null;
    }

    public function boletoDueDate()
    {
        return $this->success() ? $this->responseToArray()['payment']['due_date'] : null;
    }

    public function paymentStatus()
    {
        return $this->success() ? $this->responseToArray()['payment']['status'] : null;
    }

    public function orderID()
    {
        return ($this->http_code == 200) ? $this->responseToArray()['order_id'] : null;
    }

    public function isPaid(): bool
    {
        return $this->paymentStatus() === Gateway::PAYMENT_STATUS_PAID;
    }

    public function isPending(): bool
    {
        return $this->paymentStatus() === Gateway::PAYMENT_STATUS_PENDING;
    }

    public function isCanceled(): bool
    {
        return $this->paymentStatus() === Gateway::PAYMENT_STATUS_CANCELED;
    }

    public function isFailed(): bool
    {
        return $this->paymentStatus() === Gateway::PAYMENT_STATUS_FAILED;
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
        } else if ($this->http_code === 200 and $response_decoded['status'] == Gateway::PAYMENT_ERROR)
        {
                $this->addMsgError($response_decoded['message']);
        } else 
        {
            $this->addMsgError($this->getRawResponse());
        }

        return $this->errors;
    }

    private function addMsgError(string $msg)
    {
        $this->errors[] = $msg;
    }
}
