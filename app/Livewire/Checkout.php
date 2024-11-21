<?php

namespace App\Livewire;

use App\Enums\CheckoutStepsEnum;
use App\Exceptions\PaymentException;
use App\Livewire\Forms\AddressForm;
use App\Livewire\Forms\UserForm;
use App\Mail\OrderCreatedMail;
use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Services\UserService;
use Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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
        // $this->user->email = config("payment.mercadopago.buyer_nickname");
        // $this->user->cpf = "12345678909";
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
        // $data['payer']['identification']['number'] = str_replace(['.', '-'], '', $data['payer']['identification']['number']);

        try {
            $payment = $checkoutService->creditCardPayment($data);
            $user = $userService->store($this->user->all(), $this->address->all());
            $order = $orderService->update($this->cart['id'], $payment, $user, $this->address->all());

            Mail::to($user->email)->queue(new OrderCreatedMail($order));

            $this->responsePayment();

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

            if ($data['method'] === 'pix') {
                $payment = $checkoutService->pixPayment($data, $this->user->all());
            } else {
                $payment = $checkoutService->bankSlipPayment($data, $this->user->all(),$this->address->all());
            }

            $user = $userService->store($this->user->all(), $this->address->all());
            $order = $orderService->update($this->cart['id'], $payment, $user, $this->address->all());

            Mail::to($user->email)->queue(new OrderCreatedMail($order));

            $this->responsePayment();

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

    public function responsePayment()
    {
        $url = URL::temporarySignedRoute(
            name: 'checkout.result',
            expiration: 3600,
            parameters: [
                'order_id' => $this->cart['id']
            ]
        );

        $this->flash('success', 'Pagamento aprovado! ', [], $url);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
