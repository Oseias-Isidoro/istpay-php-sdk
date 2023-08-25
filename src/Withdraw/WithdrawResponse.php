<?php

namespace IstpaySDK\SDK\Withdraw;

use Psr\Http\Message\ResponseInterface;

class WithdrawResponse extends \IstpaySDK\SDK\Response
{
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
    }

    public function getErrors(): array
    {
        $response_decoded = $this->responseToArray();

        if ($this->clientError())
        {
            if ($this->http_code === 422)
            {
                foreach ($response_decoded['errors'] as $value)
                {
                    $this->addMsgError($value[0]);
                }
            } else if (in_array($this->http_code, [400, 404])) {
                $this->addMsgError($response_decoded['message']);
            }

        } else if ($this->serverError())
        {
            $this->addMsgError($this->getRawResponse());
        }

        return $this->errors;
    }
}