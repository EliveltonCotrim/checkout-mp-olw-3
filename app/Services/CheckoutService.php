<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Exceptions\PaymentException;
use App\Models\Order;
use Database\Seeders\OrderSeeder;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Str;

class CheckoutService
{
    protected $paymentClient;
    protected $request_options;

    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('payment.mercadopago.access_token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        // Inicializar o paymentClient do SDK do Mercado Pago
        $idempoency_key = Str::uuid();
        $this->paymentClient = new PaymentClient();
        $this->request_options = new RequestOptions();
        $this->request_options->setCustomHeaders(["X-Idempotency-Key: {$idempoency_key}"]);
    }

    public function loadCart(): array
    {
        $cart = Order::with('skus.product', 'skus.features')
            ->where('status', OrderStatusEnum::CART)
            ->where(function ($query) {

                $query->where('session_id', session()->getId());

                if (auth()->check()) {
                    $query->orWhere('user_id', auth()->id());
                }

            })->first();

        if (!$cart) {
            if (config('app.env') == 'local' || config('app.env') == 'testing') {
                $seed = new OrderSeeder();
                $seed->run(session()->getId());
                return $this->loadCart();
            }
        }

        return $cart->toArray();
    }

    public function creditCardPayment($data)
    {
        // $idempoency_key = Str::uuid();
        // $client = new PaymentClient();
        // $request_options = new RequestOptions();
        // $request_options->setCustomHeaders(["X-Idempotency-Key: {$idempoency_key}"]);

        $payment = $this->paymentClient->create([
            "transaction_amount" => (float) $data['transaction_amount'],
            "token" => $data['token'],
            "description" => $data['description'],
            "installments" => (int) $data['installments'],
            "payment_method_id" => $data['payment_method_id'],
            "issuer_id" => (int) $data['issuer_id'],
            "payer" => [
                "email" => $data['payer']['email'],
                "identification" => [
                    "type" => $data['payer']['identification']['type'],
                    "number" => $data['payer']['identification']['number']
                ]
            ]
        ], $this->request_options);

        throw_if(
            !$payment->id || $payment->status === 'rejected',
            PaymentException::class,
            $payment?->error?->message ?? "Verifique os dados do cartão de crédito e tente novamente."
        );

        return $payment;
    }

    public function pixPayment($data, $user)
    {
        $payment = $this->paymentClient->create([
            "transaction_amount" => (float) $data['amount'],
            "payment_method_id" => $data['method'],
            "payer" => [
                "email" => $user['email']
            ]
        ], $this->request_options);

        throw_if(
            !$payment->id || $payment->status === 'rejected',
            PaymentException::class,
            $payment?->error?->message ?? "Pix rejeitado, verifique seus dados e tente navamente."
        );

        return $payment;
    }

    public function bankSlipPayment($data, $user, $address)
    {
        $payment = $this->paymentClient->create([
            "transaction_amount" => (float) $data['amount'],
            "payment_method_id" => $data['method'],
            ...$this->buildPayer($user, $address)
        ], $this->request_options);

        throw_if(
            !$payment->id || $payment->status === 'reject',
            PaymentException::class,
            $payment?->error?->message ?? 'Boleto rejeitado, verifique seus dados e tente novamente.'
        );

        return $payment;
    }

    public function buildPayer($user, $address): array
    {
        $firstName = explode(' ', $user['name'])[0];
        $data = array(
            "payer" => [
                "email" => $user['email'],
                "first_name" => $firstName,
                "last_name" => Str::of($user['name'])->after($firstName)->trim()->value(),
                "identification" => [
                    "type" => "CPF",
                    "number" => $user['cpf']
                ],
                "address" => [
                    "zip_code" => $address['zipcode'],
                    "city" => $address['city'],
                    "street_name" => $address['address'],
                    "street_number" => $address['number'],
                    "neighborhood" => $address['district'],
                    "federal_unit" => $address['state']
                ]
            ]
        );

        return $data;
    }
}