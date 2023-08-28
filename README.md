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

