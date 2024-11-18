<x-mail::message>
    # Pedido criado

    O pedido {{ $order->id }} foi criado com sucesso.

    Valor Total: @money($order->total)

    Obrigado por comprar conosco!
</x-mail::message>
