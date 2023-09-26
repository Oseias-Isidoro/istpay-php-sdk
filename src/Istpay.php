<?php

declare(strict_types=1);

namespace IstpaySDK\SDK;

use IstpaySDK\SDK\AbandonedCart\AbandonedCard;
use IstpaySDK\SDK\BuyButtons\BuyButtons;
use IstpaySDK\SDK\Customers\Customers;
use IstpaySDK\SDK\Dashboard\Dashboard;
use IstpaySDK\SDK\Discounts\Discounts;
use IstpaySDK\SDK\Gateway\Gateway;
use IstpaySDK\SDK\Integrations\Integrations;
use IstpaySDK\SDK\Notifications\Notifications;
use IstpaySDK\SDK\OrderBumps\OrderBumps;
use IstpaySDK\SDK\Orders\Orders;
use IstpaySDK\SDK\Payments\Payments;
use IstpaySDK\SDK\Pixels\Pixels;
use IstpaySDK\SDK\Products\Products;
use IstpaySDK\SDK\ShippingOptions\ShippingOptions;
use IstpaySDK\SDK\Shop\Shop;
use IstpaySDK\SDK\UserDevices\UserDevices;
use IstpaySDK\SDK\Withdraw\Withdraw;

class Istpay extends Request
{
    const ENVIRONMENT_PROD = 'prod';
    const ENVIRONMENT_HML = 'hml';

    public function __construct(string $token, string $environment = self::ENVIRONMENT_PROD)
    {
        parent::__construct($token, $environment);
    }

    public function gateway(): Gateway
    {
        return new Gateway($this->getToken(), $this->getEnvironment());
    }

    public function withdraw(): Withdraw
    {
        return new Withdraw($this->getToken(), $this->getEnvironment());
    }

    public function shop(): Shop
    {
        return new Shop($this->getToken(), $this->getEnvironment());
    }

    public function dashboard(): Dashboard
    {
        return new Dashboard($this->getToken(), $this->getEnvironment());
    }

    public function orderBumps(): OrderBumps
    {
        return new OrderBumps($this->getToken(), $this->getEnvironment());
    }

    public function pixels(): Pixels
    {
        return new Pixels($this->getToken(), $this->getEnvironment());
    }

    public function discounts(): Discounts
    {
        return new Discounts($this->getToken(), $this->getEnvironment());
    }

    public function buyButtons(): BuyButtons
    {
        return new BuyButtons($this->getToken(), $this->getEnvironment());
    }

    public function customers(): Customers
    {
        return new Customers($this->getToken(), $this->getEnvironment());
    }

    public function orders(): Orders
    {
        return new Orders($this->getToken(), $this->getEnvironment());
    }

    public function products(): Products
    {
        return new Products($this->getToken(), $this->getEnvironment());
    }

    public function abandonedCart(): AbandonedCard
    {
        return new AbandonedCard($this->getToken(), $this->getEnvironment());
    }

    public function payments(): Payments
    {
        return new Payments($this->getToken(), $this->getEnvironment());
    }

    public function shippingOptions(): ShippingOptions
    {
        return new ShippingOptions($this->getToken(), $this->getEnvironment());
    }

    public function userDevices(): UserDevices
    {
        return new UserDevices($this->getToken(), $this->getEnvironment());
    }

    public function notifications(): Notifications
    {
        return new Notifications($this->getToken(), $this->getEnvironment());
    }

    public function integrations(): Integrations
    {
        return new Integrations($this->getToken(), $this->getEnvironment());
    }
}