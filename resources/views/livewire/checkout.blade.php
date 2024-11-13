<div class="bg-tertiary-900" x-data="checkout()">
    <!-- Background color split screen for large screens -->
    <div class="fixed left-0 top-0 hidden h-full w-1/2 bg-tertiary-900 lg:block" aria-hidden="true"></div>
    <div class="fixed right-0 top-0 hidden h-full w-1/2 bg-tertiary-800 lg:block" aria-hidden="true"></div>


    <div class="relative mx-auto grid max-w-7xl grid-cols-1 gap-x-16 lg:grid-cols-2 lg:px-8 lg:pt-16">

        <section aria-labelledby="summary-heading"
            class="bg-tertiary-800 py-12 text-indigo-300 md:px-10 lg:col-start-2 lg:row-start-1 lg:mx-auto lg:w-full lg:max-w-lg lg:bg-transparent lg:px-0 lg:pb-24 lg:pt-0">

            <div class="mx-auto max-w-2xl px-4 lg:max-w-none lg:px-0">

                <dl>
                    <dt class="text-lg font-medium text-primary-200">Resumo</dt>
                </dl>

                <x-checkout.product-list>
                    @foreach ($this->cart['skus'] as $sku)
                        <x-checkout.product-item name="{{ $sku['name'] }}" price="{{ $sku['price'] }}"
                            :features="collect($sku['features'])->map(
                                fn($feature) => $feature['name'] . ': ' . $feature['pivot']['value'],
                            )" :quantity="$sku['pivot']['quantity']" />
                    @endforeach
                </x-checkout.product-list>

                <dl class="space-y-5 border-t border-white border-opacity-10 pt-6 text-sm font-medium">
                    <x-checkout.summary-item title="Subtotal" total="{{ $this->cart['total'] }}" />
                    <x-checkout.summary-item title="Frete" total="0,00" />
                    <x-checkout.summary-item title="Total" total="{{ $this->cart['total'] }}" :is-last="true" />
                </dl>
            </div>
        </section>

        <section aria-labelledby="payment-and-shipping-heading"
            class="py-16 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:w-full lg:max-w-lg lg:pb-24 lg:pt-0">
            <div class="mx-auto max-w-2xl px-4 lg:max-w-none lg:px-0">
                <div>
                    <div class="flex flex-row items-center text-white">
                        <nav class="flex mb-4" arial-label="Breadcrumb">
                            <ol class="text-xs inline-flex items-center space-x-1 md:space-x-3">
                                <li @class([
                                    'inline-flex',
                                    'font-bold text-primary-200' =>
                                        $step === CheckoutStepsEnum::INFORMATION->value,
                                ])>
                                    <span>{{ CheckoutStepsEnum::INFORMATION->getName() }}</span>
                                </li>
                                <li @class([
                                    'inline-flex items-center',
                                    'font-bold text-primary-200' =>
                                        $step === CheckoutStepsEnum::SHIPPING->value,
                                ])>
                                    <svg @class([
                                        'w-3 h-3  mx-1',
                                        'text-primary-200' => $step === CheckoutStepsEnum::SHIPPING->value,
                                    ]) aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span>{{ CheckoutStepsEnum::SHIPPING->getName() }}</span>
                                </li>
                                <li @class([
                                    'inline-flex items-center',
                                    'font-bold text-primary-200' => $step === CheckoutStepsEnum::PAYMENT->value,
                                ])>
                                    <svg @class([
                                        'w-3 h-3  mx-1',
                                        'text-primary-200' => $step === CheckoutStepsEnum::PAYMENT->value,
                                    ]) aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span>{{ CheckoutStepsEnum::PAYMENT->getName() }}</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    @if ($step === CheckoutStepsEnum::INFORMATION->value)
                        <x-checkout.information-form />
                    @elseif ($step === CheckoutStepsEnum::SHIPPING->value)
                        <x-checkout.shipping-form :user="$user" :address="$address" />
                    @elseif ($step === CheckoutStepsEnum::PAYMENT->value)
                        <x-checkout.payment-form :user="$user" :address="$address" :cart="$cart"/>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
