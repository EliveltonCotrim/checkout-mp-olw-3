<?php

namespace App\Livewire;

use App\Enums\CheckoutStepsEnum;
use App\Exceptions\PaymentException;
use App\Livewire\Forms\AddressForm;
use App\Livewire\Forms\UserForm;
use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Services\UserService;
use Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Checkout extends Component
{
    use LivewireAlert;
    public array $cart = [];
    public int $step = CheckoutStepsEnum::INFORMATION->value;
    public ?int $method = null;

    public UserForm $user;
    public AddressForm $address;

    public function mount(CheckoutService $checkoutService): void
    {
        $this->cart = $checkoutService->loadCart();
        $this->user->email = config("payment.mercadopago.buyer_nickname");
        $this->user->cpf = "12345678909";

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

    public function creditCardPayment(CheckoutService $checkoutService, OrderService $orderService, UserService $userService, $data)
    {

        // REmover depois
        $data['payer']['identification']['number'] = str_replace(['.', '-'], '', $data['payer']['identification']['number']);

        try {
            $payment = $checkoutService->creditCardPayment($data);
            $user = $userService->store($this->user->all(), $this->address->all());
            $order = $orderService->update( $this->cart['id'], $payment, $user, $this->address->all());

            dd($order->toArray());

            $this->alert("success", 'Pagamento aprovado!', [
                'position' => 'top',
                'timer' => 7000,
            ]);

        } catch (PaymentException $e) {
            $this->alert("error", $e->getMessage(), [
                'position' => 'top',
                'timer' => 5000,
            ]);
            // $this->addError('payment', $e->getMessage());
        } catch (\Exception $e) {

            $this->alert("error", $e->getMessage(), [
                'position' => 'top',
                'timer' => 5000,
            ]);
        }
    }

    public function pixOrBankSlipPayment(CheckoutService $checkoutService, OrderService $orderService, UserService $userService, $data)
    {
        $data['payer']['email'] = $this->user->email;

        try {
            $payment = $checkoutService->pixOrBankSlipPayment($data);
            $user = $userService->store($this->user->all(), $this->address->all());
            $order = $orderService->update( $this->cart['id'], $payment, $user, $this->address->all());

            dd($payment);

        } catch (PaymentException $e) {
            $this->alert("error", $e->getMessage(), [
                'position' => 'top',
                'timer' => 5000,
            ]);
        } catch (\Exception $e) {

            $this->alert("error", $e->getMessage(), [
                'position' => 'top',
                'timer' => 5000,
            ]);
        }

    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
