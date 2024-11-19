<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Mail\PaymentApprovedMail;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('payment.mercadopago.access_token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    public function update($external_id): void
    {
        $payment = Payment::with('order.user')->where('external_id', $external_id)->firstOrFail();

        $client = new PaymentClient();
        $request_options = new RequestOptions();
        $idempoency_key = Str::uuid();

        $request_options->setCustomHeaders(["X-Idempotency-Key: {$idempoency_key}"]);

        $mp_payment = $client->get($external_id);

        // Teste
        // $payment->status = PaymentStatusEnum::parse('rejected');

        $payment->status = PaymentStatusEnum::parse($mp_payment->status);
        $payment->save();

        if($payment->status === PaymentStatusEnum::PAID){
            $payment->approved_at = $mp_payment->date_approved;
            $payment->order->status = OrderStatusEnum::PAID;
            $payment->order->save();

            Mail::to($payment->order->user->email)->queue(new PaymentApprovedMail($payment->order));
        }

        if ($payment->status === PaymentStatusEnum::CANCELLED || $payment->status === PaymentStatusEnum::REJECTED)
        {
            $payment->order->status = OrderStatusEnum::parse($mp_payment->status);
            $payment->order->save();
        }
    }

}