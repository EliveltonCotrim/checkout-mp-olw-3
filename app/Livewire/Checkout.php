<?php

namespace App\Livewire;

use App\Enums\CheckoutStepsEnum;
use App\Livewire\Forms\AddressForm;
use App\Livewire\Forms\UserForm;
use App\Services\CheckoutService;
use Http;
use Livewire\Component;

class Checkout extends Component
{
    public array $cart = [];
    public int $step = CheckoutStepsEnum::PAYMENT->value;
    public ?int $method = null;

    public UserForm $user;
    public AddressForm $address;

    public function mount(CheckoutService $checkoutService): void
    {
        $this->cart = $checkoutService->loadCart();

    }

    public function findAddress()
    {
        $this->address->findAddress();
    }

    public function submitInformationStep()
    {
        $this->user->validate();
        $this->address->validate();
        $this->step = CheckoutStepsEnum::SHIPPING->value;
    }

    public function submitShippingStep()
    {
        $this->step = CheckoutStepsEnum::PAYMENT->value;
    }

    public function creditCardPayment($data)
    {
        dd($data);
    }

    public function pixOrBankSlipPayment($data)
    {
        dd($data);

    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
