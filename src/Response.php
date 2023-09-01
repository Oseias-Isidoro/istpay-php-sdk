<?php

declare(strict_types=1);

namespace IstpaySDK\SDK;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response
{
    protected ResponseInterface $response;
    public int $http_code;
    protected array $errors = [];

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
        return ($this->http_code >= 200 and $this->http_code <= 299);
    }

    public function responseToArray()
    {
        return json_decode((string) $this->getRawResponse(), true);
    }

    public function responseToObject()
    {
        return json_decode((string) $this->getRawResponse());
    }

    public function clientError(): bool
    {
        return ($this->http_code >= 400 and $this->http_code <= 499);
    }

    public function serverError(): bool
    {
        return ($this->http_code >= 500 and $this->http_code <= 599);
    }

    public function getErrors(): array
    {
        $response_decoded = $this->responseToArray();

        if ($this->clientError())
        {
            foreach ($response_decoded['errors'] as $value)
            {
                $this->addMsgError($value[0]);
            }
        } else if ($this->serverError())
        {
            $this->addMsgError((string) $this->getRawResponse());
        }

        return $this->errors;
    }

    protected function addMsgError(string $msg)
    {
        $this->errors[] = $msg;
    }
}