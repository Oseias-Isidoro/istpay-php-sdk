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

$istpay = (new Istpay('token'));
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
        "street" => 'Rua floresta',
        "number" => '434',
        "district" => 'bairo',
        "adjunct" => 'sem complemento',
        "state" => 'PR',
        "codIbge" => '0000000'
    ]
]);
$istpayGateway->setShipping(["title" => "free", "price" => 0]);
$istpayGateway->setNotificationUrl('https://mysystem.com.br/webhook');
$istpayGateway->setCustomerIP('45.234.1.68');

$response = $istpayGateway->boleto();

echo $response->success(); // TRUE se a cobraça foi gerada com sucesso
echo $response->orderID(); // ID da order gerada na Istpay Checkout, retorna NULL em caso de erro 422 (http code error)
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

// os method listados aqui são os que mudam seu comportamento quando a cobrança for no pix
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

// os method listados aqui são os que mudam seu comportamento quando a cobrança for no cartão de credito
echo $response->paymentCode(); // retornara NULL
echo $response->boletoPDFLink(); // retornara NULL
echo $response->boletoDueDate(); // retornara NULL
```