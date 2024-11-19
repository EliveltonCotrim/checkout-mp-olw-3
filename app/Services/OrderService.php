<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;

class OrderService
{
    public function update(int $orderId, $payment, $user, $address)
    {
        $order = Order::find($orderId);
        $order->status = OrderStatusEnum::parse($payment->status);
        $order->user_id = $user->id;
        $order->save();

        $order->payments()->create([
            "external_id" => $payment->id,
            "method" => PaymentMethodEnum::parse($payment->payment_type_id),
            "status" => PaymentStatusEnum::parse($payment->status),
            "installments" => $payment->installments ?? null,
            "approved_at" => $payment->date_approved ?? null,
            "qr_code_64" => $payment?->point_of_interaction?->transaction_data?->qr_code_base64 ?? null,
            "qr_code" => $payment?->point_of_interaction?->transaction_data?->qr_code ?? null,
            "ticket_url" => $payment?->point_of_interaction?->transaction_data?->ticket_url ?? $payment?->transaction_details?->external_resource_url,
        ]);

        $order->shippings()->create([
            "address" => $address['address'],
            "number"=> $address['number'],
            "city" => $address['city'],
            "state" => $address['state'],
            "zipcode" => $address['zipcode'],
            "district"=> $address['district'],
            "complement"=> $address['complement'],
        ]);

        $order->load(['payments', 'shippings']);

        return $order;
    }

}