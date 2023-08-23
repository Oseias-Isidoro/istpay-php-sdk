<?php

use IstpaySDK\SDK\Istpay;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Acme\\Test\\', __DIR__);

$istpay = (new Istpay('1077|pOrS1JYEOYCqGrSaoo8leyXYXTI6MxK32b31Fw0P'));

$istpayGateway = $istpay->gateway();

$istpayGateway->setCart([[
    "title" => 'istpay wordpress',
    "price" => 6,
    "quantity" => 1
]]);
$istpayGateway->setCustomer([
    "name" => 'oseias isdioro',
    "document" => '11128991977',
    "phone" => '45 999153083',
    "email" => 'oseiasisidoro@gmail.com',
    "address" => [
        "zipcode" => '85814516',
        "street" => 'Rua Adelaide Bueno da Cruz',
        "number" => '434',
        "district" => 'Floresta',
        "adjunct" => 'sem complemento',
        "state" => 'PR',
        "codIbge" => '3106200'
    ]
]);
$istpayGateway->setShipping(["title" => "free", "price" => 0]);
$istpayGateway->setCardFlag('visa');
$istpayGateway->setTitular('oseias isidoro');
$istpayGateway->setCvv('123');
$istpayGateway->setCardNumber('2345 5466 6546 564');
$istpayGateway->setValidadeCartao('04/25');
$istpayGateway->setInstallments(2);
$istpayGateway->setCustomerIP('::');

$istpayGateway->setNotificationUrl('https://google.com.br');

var_dump($istpayGateway->installments('visa', 10.6));

/* $response = $istpayGateway->pix();
var_dump($response->success());
var_dump($response->orderID());
var_dump($response->getErrors());
var_dump($response->paymentStatus());
var_dump($response->transactionID());
var_dump($response->boletoPDFLink());
var_dump($response->boletoDueDate());*/
