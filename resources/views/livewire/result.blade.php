<div class="py-10 bg-tertiary-900 min-h-screen w-full flex flex-col items-center justify-center">
    <div class="flex flex-col w-[550px] rounded-md border border-primary-200 px-10 py-16">
        <h1 class="text-white text-lg font-bold">Obrigado por comprar em nossa loja</h1>
        <h2 class="text-white">Seu pedido foi realizado com sucesso</h2>

        <div x-data="{ showProducts: false }" class="flex flex-col divide-y divide-primary-200 divide-opacity-10 mt-4 space-y-2">
            <div class="text-white flex flex-row items-center justify-between pt-2">
                <span>Numero do pedido</span>
                <h3>{{ $order_id }}</h3>
            </div>

            <div class="text-white flex flex-row items-center justify-between pt-2">
                <span>Status do Pedido</span>
                <h3 class="{{ $order->status->getStyles() }}">{{ $order->status->getName() }}</h3>
            </div>

            <div class="text-white flex flex-row items-center justify-between pt-2">
                <span>Metodo de pagamento</span>
                <h3>{{ $order->payments->last()->method->getName() }}</h3>
            </div>

            <div class="text-white flex flex-row items-center justify-between pt-2">
                <span>Status do Pagamento</span>
                <h3 class="{{ $order->payments->last()->status->getStyles() }}">
                    {{ $order->payments->last()->status->getName() }}</h3>
            </div>

            <div class="pt-2 text-white cursor-pointer" x-on:click="showProducts = !showProducts">
                <div class="flex items-center justify-between">
                    <span>Produtos</span>
                    <button>
                        <svg x-show="showProducts == false" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-6 text-primary-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                        <svg x-show="showProducts == true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-6 text-primary-200">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                        </svg>
                    </button>
                </div>

                @foreach ($order->skus as $product)
                    <div x-show="showProducts" x-transition
                        class="flex px-1 flex-row items-center justify-between text-white pt-2">
                        <span class="text-primary-200">
                            {{ $product->name }}
                        </span>
                        <span class="text-green-300 text-sm">
                            @money($product->price)
                            <div>
                                <small class="text-white">
                                    x
                                </small>
                                <span class="text-white">
                                    {{ $product->pivot->quantity }}
                                </span>
                            </div>
                        </span>
                    </div>
                @endforeach
            </div>
            <div></div>
        </div>

        @if ($order->payments->last()->method == PaymentMethodEnum::PIX)
            <div class="col-span-4 sm:col-span-8 flex flex-col items-center justify-end gap-y-6 mt-8">
                <img src="data:image/jpeg;base64,{{ $order->payments->last()->qr_code_64 }}"
                    class="border border-2 rounded-md p-1 border-primary-200 w-[200px]">

                <x-primary-button x-clipboard="'{{ $order->payments->last()->qr_code }}'"
                    wire:click="showSuccessAlert()">
                    Copiar QR Code
                </x-primary-button>
            </div>
            <div class="flex flex-col justify-start text-white space-y-4">
                <h3 class="text-white my-3">Como pagar?</h3>
                <div class="flex space-x-3">
                    <span
                        class="border border-2 border-primary-200 text-primary-200 p-3 flex items-center justify-center rounded-full w-4 h-4">
                        1
                    </span>
                    <span class="text-sm">
                        Entre no app ou site do seu banco e escolha a opção
                        de pagamento via Pix.
                    </span>
                </div>
                <div class="flex space-x-3">
                    <span
                        class="border border-2 border-primary-200 text-primary-200 p-3 flex items-center justify-center rounded-full w-4 h-4">
                        2
                    </span>
                    <span class="text-sm">
                        Escaneie o código QR ou copie e cole o código de pagamento.
                    </span>
                </div>
                <div class="flex space-x-3">
                    <span
                        class="border border-2 border-primary-200 text-primary-200 p-3 flex items-center justify-center rounded-full w-4 h-4">
                        3
                    </span>
                    <span class="text-sm">
                        Pronto! O pagamento será creditado na hora e você receberá um e-mail de confirmação.
                    </span>
                </div>
                <div class="flex items-start justify-start">
                    <small class="my-4">O Pix tem um limite diário de transferências. Para mais informações, por
                        favor, consulte seu banco.</small>
                </div>
            </div>
        @endif

        @if ($order->payments->last()->method == PaymentMethodEnum::BANK_SLIP)
            <div class="col-span-4 sm:col-span-8 flex flex-col items-center justify-end gap-y-8 mt-8">
                <div class="w-full">
                    <x-input-label>Código de barras</x-input-label>
                    <x-input-with-copy class="mt-2" :value="$order->payments->last()->digitable_line"></x-input-with-copy>
                </div>
                <x-primary-button @click="window.open('{{ $order->payments->last()->ticket_url }}', '_blank')">
                    Baixar boleto
                </x-primary-button>
            </div>
        @endif
    </div>
</div>
