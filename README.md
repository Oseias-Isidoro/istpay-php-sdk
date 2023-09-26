# istpay-php-sdk
SDK para a REST API da Istpay Bank

# INSTALAÇÃO
```
composer require istpay/istpay-php-sdk
```

# BOLETO
Gerando uma cobrança no Boleto.

```php
use IstpaySDK\SDK\Istpay;

$istpay = new Istpay('token');
$istpayGateway = $istpay->gateway();

$istpayGateway->setCart([
    [
        "title" => 'product 1',
        "price" => 6,
        "quantity" => 1
    ],
    [
        "title" => 'product 2',
        "price" => 12,
        "quantity" => 2
    ]
]);
$istpayGateway->setCustomer([
    "name" => 'fulano da silva',
    "document" => '111.222.333-44',
    "phone" => '55 999112233',
    "email" => 'fulanodasilva@gmail.com',
    "address" => [
        "zipcode" => '0000000',
        "city" => 'Cidade',
        "street" => 'Rua',
        "number" => '434',
        "district" => 'Bairro',
        "adjunct" => 'sem complemento',
        "state" => 'Estado',
        "codIbge" => '0000000'
    ]
]);
$istpayGateway->setShipping(["title" => "free", "price" => 0]);
$istpayGateway->setNotificationUrl('https://mysystem.com.br/webhook');
$istpayGateway->setCustomerIP('45.234.1.68');

$response = $istpayGateway->boleto();

echo $response->success(); // TRUE se a cobrança foi gerada com sucesso
echo $response->orderID(); // ID da ordem gerada na Istpay Checkout, retorna NULL em caso de erro 422 (http code error)
var_dump($response->getErrors()); // Array de erros
echo $response->paymentCode(); // barcode do boleto
echo $response->paymentStatus(); // status da cobrança, pode ser 'paid', 'pending', 'canceled' ou 'failed', em caso de erro 422 retornara  NULL
echo $response->transactionID(); // id da transação, em caso de erro pode retornar NULL
echo $response->boletoPDFLink(); // link para acessar o PDF do boleto
echo $response->boletoDueDate(); // data de vencimento do boleto
echo $response->isPaid(); // TRUE se o status retornado for 'paid'
echo $response->isPending(); // TRUE se o status retornado for 'pending'
echo $response->isCanceled(); // TRUE se o status retornado for 'canceled'
echo $response->isFailed(); // TRUE se o status retornado for 'failed'
```

# PIX
Gerando uma cobrança no PIX.

```php
//apenas mude essa linha do codigo acima 
$response = $istpayGateway->boleto();
//para 
$response = $istpayGateway->pix();

// os metodos listados aqui são os que mudam seu comportamento quando a cobrança for no pix
echo $response->paymentCode(); // chave pix para pagamento
echo $response->boletoPDFLink(); // retornara NULL
echo $response->boletoDueDate(); // retornara NULL
```

# CARTÃO DE CREDITO
Gerando uma cobrança no Cartão de credito.

```php
// campos a acrecentar
$istpayGateway->setCardFlag('visa');
$istpayGateway->setHolder('fulano da silva');
$istpayGateway->setCvv('123');
$istpayGateway->setCardHolderDocument('111.222.333-44'); // cpf do titular do cartão
$istpayGateway->setCardNumber('111 222 3333 4444');
$istpayGateway->setCardExpiringDate('04/25'); // mm/yy
$istpayGateway->setInstallments(4); // de 1 a 12

//apenas mude essa linha do codigo acima 
$response = $istpayGateway->boleto();
//para 
$response = $istpayGateway->creditCard();

// os metodos listados aqui são os que mudam seu comportamento quando a cobrança for no cartão de credito
echo $response->paymentCode(); // retornara NULL
echo $response->boletoPDFLink(); // retornara NULL
echo $response->boletoDueDate(); // retornara NULL
```
___
# WITHDRAW

* Verificar chave pix
```php
use IstpaySDK\SDK\Istpay;

$istpay = new Istpay('token');
$istpayWithdraw = $istpay->withdraw();

$response = $istpayWithdraw->checkPixKey([
    'pix_key' => 'chave pix',
    'type_key' => 'tipo da chave' // document, phoneNumber, email, randomKey, paymentCode
]);

if ($response->success())
{
    $res_obj = $response->responseToObject();
    echo $res_obj->value;                               // 0,
    echo $res_obj->typeKey;                             // "cpf",
    echo $res_obj->destinationAccountOwnerName;         // "Salvatore Strandburg",
    echo $res_obj->destinationAccountOwnerSocialNumber; // "***.893.450-**",
    echo $res_obj->destinationAccountOwnerSocialType;   // "individual",
    echo $res_obj->destinationAccountType;              // "payment",
    echo $res_obj->destinationBankIspb;                 // "39231527",
    echo $res_obj->destinationBank;                     // "UNICRED COSTA DO SOL RJ",
    echo $res_obj->destinationAgency;                   // "3964",
    echo $res_obj->destinationAccountNumber;            // "6981656148545163",
    echo $res_obj->active;                              // true
} else {
    var_dump($response->getErrors()); // array de erros
}
```

* Transferência via PIX
```php
use IstpaySDK\SDK\Istpay;

$istpay = new Istpay('token');
$istpayWithdraw = $istpay->withdraw();

$response = $istpayWithdraw->pixTransfer([
    'pix_key' => 'chave pix',
    'type_key' => 'tipo da chave', // document, phoneNumber, email, randomKey, paymentCode
    'amount' => 5,
    'internal_identifier' => "user-0001"
]);

if ($response->success())
{
    $res_obj = $response->responseToObject();
    echo $res_obj->status; 
    echo $res_obj->amount;
    echo $res_obj->pix_key;
    echo $res_obj->key_type;
    echo $res_obj->transaction_id;
    echo $res_obj->message;
} else {
    var_dump($response->getErrors()); // array de erros
}
```

# Demais endpoints

### Carrinho Abandonado

* Listar carrinhos abandonados por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->abandonedCart();

$response = $istpay->get();

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}

```

### Buy Buttons

* Listar botões de compra por shop id

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->buyButtons();

$response = $istpay->get(
    123, //shop_id
    [
        'product_ids' => [1, 2, 3] // opcional
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}

```
### Customers

* Listar clientes por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->customers();

$response = $istpay->get(
    [
        'id' => 12, // opcional
        'name' => 'fulano da silva', // opcional
        'email' => 'fulanodasilva@gmail.com', // opcional
        'document' => '111.222.333-44' // opcional
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

### Dashboard

* Listar dados dashboard do shop via token

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->dashboard();

$response = $istpay->get(
    'shop_token',
    '01/01/2022',
    '01/01/2023',
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

### Discounts

* Listar descontos por shop id

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->discounts();

$response = $istpay->get(
    123, // shop_id
    [
        'product_id' => 123, // opcional
        'payment_method' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_METHOD_PIX, // pix|boleto|credit_Cart - opcional
        'automatic_apply' => 1, // opcional
        'newsletter_abandoned_carts' => 1, // opcional
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
* Validar desconto via cupom

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->discounts();

$response = $istpay->validateCoupon(
    123, // shop_id
    [
        'code' => 'OIVCGC74F',
        'total_order' => 100,
        'email_customer' => "fulanodasilva@gmail.com",
        'payment_method' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_METHOD_PIX, //pix|boleto|credit_Cart
        'product_ids' => "999,1000",
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Notifications

* Listar notificações por shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->notifications();

$response = $istpay->list('shop_token');

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Order Bumps

* Listar order bumps por shop id

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->orderBumps();

$response = $istpay->get(
    123, // shop_id
    [
        'product_id' => 10471, // opcinal
        'products_ids' => '10471,5435', // opcinal
        'rules_to_show' => 'always', // opcinal
        'values_rules_to_show' => '>100', // opcinal
        'payment_method' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_METHOD_CREDIT_CARD, //pix|boleto|credit_Cart - opcinal
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Orders

* Listar pedidos por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->orders();

$response = $istpay->get([
        'id' => 10471, // opcinal
        'start_date' => '01/01/2023', // opcinal
        'end_date' => '01/09/2023', // opcinal
        'product' => 343, // opcinal
        'customer' => 455, // opcinal
        'external_identification' => '2dc0204d-4971-4bf3-a1e8-13c8fnf65sp784ce55', // opcinal
        'payment_method' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_METHOD_CREDIT_CARD, //pix|boleto|credit_Cart - opcinal
    ]);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Payments

* Listar pagamentos pedidos por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->payments();

$response = $istpay->get([
        'order_id' => 10471, // opcinal
        'status' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_STATUS_PAID, // paid|failed|pending|canceled - opcional
        'method' => \IstpaySDK\SDK\Gateway\Gateway::PAYMENT_METHOD_CREDIT_CARD, //pix|boleto|credit_Cart - opcional
    ]);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Pixels

* Listar pixels por shop id

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->pixels();

$response = $istpay->get(
    5 // shop_id
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Shipping Options

* Listar opções de frete por shop id

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->shippingOptions();

$response = $istpay->get(
    5, // shop_id
    [
        'total_amount_items_order' => 100, // opcional
        'product_ids' => '1,2,3', // opcional
        'states' => 'PR', // opcional
    ]   
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Shop

* Listar dados do shop via token

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->shop();

$response = $istpay->get();

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

### User Devices

* Listar dispositivos registrados  por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->userDevices();

$response = $istpay->list('shop_token');

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
* Registrar novo dispositivo  por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->userDevices();

$response = $istpay->store(
    'shop_token',
    'device_id'
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
### Products

* Criar produto digital por shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->products();

$response = $istpay->create([
    'title' => 'auto test',
    'description' => 'description auto test',
    'price' => 10.5,
    'redirect_url_card' => 'url',
    'type' => Products::TYPE_DIGITAL,
]);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```
* Criar produto digital por shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->products();

$response = $istpay->update(
    3423, // product_id
    [
        'title' => 'auto test',
        'description' => 'description auto test',
        'price' => 10.5,
        'redirect_url_card' => 'url',
        'type' => Products::TYPE_DIGITAL,
    ]
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

* Deletar produto digital por shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->products();

$response = $istpay->delete(
    3423, // product_id
);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

* Listar produtos por Shop

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->products();

$response = $istpay->get([
    'id' => 123, //product_id - opcional
    'title' => 'product title', // opcional
]);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```

### Integrações

* Integrar com o WooCommerce

```php
use IstpaySDK\SDK\Istpay;

$istpay = (new Istpay('token'))->integrations();

$response = $istpay->woo_integration([
    "woo_url_store" => "www.myshop.com",
    "woo_consumer_key" => "ck_47549572a85fdbf8d4a82b85gfd877d4d7d4e2da",
    "woo_consumer_secret" => "cs_f5167a95f9ce7ecb62fhgd6c193f758052554df2",
    "allow_woo_cart" => false
]);

if ($response->success())
{
    var_dump($response->responseToArray());
} else {
    var_dump($response->getErrors()); // array de erros
}
```