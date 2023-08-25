<?php

namespace IstpaySDK\SDK\Withdraw;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Istpay;
use IstpaySDK\SDK\Request;

class Withdraw extends Request
{
    const WITHDRAW_URI = 'withdraw/v1/';

    public function __construct(string $token, string $environment = Istpay::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    /**
     * @throws GuzzleException
     */
    public function checkPixKey(array $data): WithdrawResponse
    {
        $response = $this->request('POST', self::WITHDRAW_URI.'check_pix_key', [
            'form_params' => $data
        ]);

        return new WithdrawResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function pixTransfer(array $data): WithdrawResponse
    {
        $response = $this->request('POST', self::WITHDRAW_URI.'pix_transfer', [
            'form_params' => $data
        ]);

        return new WithdrawResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getTransferVoucher(array $data): WithdrawResponse
    {
        $response = $this->request('POST', self::WITHDRAW_URI.'get_pix_transfer_voucher', [
            'form_params' => $data
        ]);

        return new WithdrawResponse($response);
    }
}