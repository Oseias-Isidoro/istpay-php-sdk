<?php

namespace IstpaySDK\SDK\Gateway;

use GuzzleHttp\Exception\GuzzleException;
use IstpaySDK\SDK\Request;

class Gateway extends Request
{
    const PAYMENT_METHOD_PIX = 'pix';
    const PAYMENT_METHOD_BOLETO = 'ticket';
    const PAYMENT_METHOD_CREDIT_CARD = 'credit_card';

    const PAYMENT_ERROR = 'error';
    const PAYMENT_SUCCESS = 'success';

    const GATEWAY_URI = 'transparent_checkout/v1/pay';

    protected array $customer;
    protected array $cart;
    protected array $shipping;
    protected string $payment_method;
    protected string $notification_url;
    protected string $customerIP;
    protected string $card_number;
    protected int $installments;
    protected string $card_flag;
    protected string $cvv;
    protected string $card_expiring_date;
    protected string $holder;
    protected string $card_holder_document;

    protected bool $send_email = false;

    public function __construct(string $token, string $environment = 'prod')
    {
        parent::__construct($token, $environment);
    }

    public function formatData(): array
    {
        return [
            "send_email" => $this->send_email,
            "customer" => $this->customer,
            "cart" => [ "items" => $this->cart ],
            "shipping" => $this->shipping,
            "payment_method" => $this->payment_method,
            "notification_url" => $this->notification_url,
            'customer_ip' => $this->getCustomerIP()
        ];
    }

    public function formatDataForCreditCard(): array
    {
        $data = $this->formatData();

        $card_info = [
            "titular_cartao" => $this->holder,
            'card_holder_document' => $this->card_holder_document,
            'card_number' => $this->card_number,
            'installments' => $this->installments,
            'card_payment_method_id' => $this->card_flag,
            'cvv' => $this->cvv,
            'validade_cartao' => $this->card_expiring_date,
        ];

        return array_merge($data, $card_info);
    }

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $customer): void
    {
        $this->customer = $customer;
    }

    public function getCart(): array
    {
        return $this->cart;
    }

    public function setCart(array $cart): void
    {
        $this->cart = $cart;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function setShipping(array $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    public function getCardHolderDocument(): string
    {
        return $this->card_holder_document;
    }

    public function setCardHolderDocument(string $card_holder_document): void
    {
        $this->card_holder_document = $card_holder_document;
    }

    public function setPaymentMethod(string $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    public function getNotificationUrl(): string
    {
        return $this->notification_url;
    }

    public function setNotificationUrl(string $notification_url): void
    {
        $this->notification_url = $notification_url;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function setCardNumber(string $card_number): void
    {
        $this->card_number = $card_number;
    }

    public function getInstallments(): int
    {
        return $this->installments;
    }

    public function setInstallments(int $installments): void
    {
        $this->installments = $installments;
    }

    public function getCardFlag(): string
    {
        return $this->card_flag;
    }

    public function setCardFlag(string $card_flag): void
    {
        $this->card_flag = $card_flag;
    }

    public function getCvv(): string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): void
    {
        $this->cvv = $cvv;
    }

    public function getCardExpiringDate(): string
    {
        return $this->card_expiring_date;
    }

    public function setCardExpiringDate(string $card_expiring_date): void
    {
        $this->card_expiring_date = $card_expiring_date;
    }

    public function getHolder(): string
    {
        return $this->holder;
    }

    public function setHolder(string $holder): void
    {
        $this->holder = $holder;
    }

    public function getCustomerIP(): string
    {
        return $this->customerIP;
    }

    public function setCustomerIP(string $customerIP)
    {
        $this->customerIP = $customerIP;
    }

    public function sendEmail(bool $send)
    {
        $this->send_email = $send;
    }

    /**
     * @throws GuzzleException
     */
    public function pix(): GatewayResponse
    {
        $this->payment_method = self::PAYMENT_METHOD_PIX;
        return $this->paymentRequest($this->formatData());
    }

    /**
     * @throws GuzzleException
     */
    public function boleto(): GatewayResponse
    {
        $this->payment_method = self::PAYMENT_METHOD_BOLETO;
        return $this->paymentRequest($this->formatData());
    }

    /**
     * @throws GuzzleException
     */
    public function creditCard(): GatewayResponse
    {
        $this->payment_method = self::PAYMENT_METHOD_CREDIT_CARD;
        return $this->paymentRequest($this->formatDataForCreditCard());
    }

    /**
     * @throws GuzzleException
     */
    private function paymentRequest(array $data): GatewayResponse
    {
        return (new GatewayResponse($this->request('POST', self::GATEWAY_URI, ['form_params' => $data])));
    }

    /**
     * @throws GuzzleException
     */
    public function installments($card_flag, $price)
    {
        $response = $this->request('GET', 'transparent_checkout/v1/installments', [
            'query' => [
                'card_flag' => $card_flag,
                'price' => $price,
            ]
        ]);

        return json_decode( $response->getBody(), true );
    }
}