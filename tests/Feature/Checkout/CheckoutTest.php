<?php

use App\Enums\CheckoutStepsEnum;
use App\Livewire\Checkout;
use Livewire\Livewire;

it('checkout is rendering', function () {
    Livewire::test(Checkout::class)
        ->assertStatus(200);
});

it('if user validation is working', function () {
    Livewire::test(Checkout::class)
        ->set('user.name', '')
        ->set('user.email', '')
        ->call('submitInformationStep')
        ->assertHasErrors(['user.*']);
});

it('if address validation is working', function () {
    Livewire::test(Checkout::class)
        ->set('user.name', 'Teste')
        ->set('user.email', 'teste@email.com')
        ->set('address.zipcode', "13720-000")
        ->set('address.address', fake()->streetAddress())
        ->set('address.city', fake()->city())
        ->set('address.state', fake()->stateAbbr())
        ->set('address.district', fake()->word())
        ->set('address.number', fake()->numberBetween(0, 300))
        ->call('submitInformationStep')
        ->assertHasNoErrors(['address.*']);
});

it('if user can access the shipping step', function () {

    Livewire::test(Checkout::class)
        ->set('user.name', "Teste")
        ->set('user.email', "teste@email.com")
        ->set('address.zipcode', "13720-000")
        ->set('address.address', fake()->streetAddress())
        ->set('address.city', fake()->city())
        ->set('address.state', fake()->stateAbbr())
        ->set('address.district', fake()->word())
        ->set('address.number', "123")
        ->call('submitInformationStep')
        ->assertSet('step', CheckoutStepsEnum::INFORMATION->value);
});

it('if validation zipCode strlen < 8 find address in viaCep', function () {

    Livewire::test(Checkout::class)
        ->set('address.zipcode', "01001-0")
        ->call('findAddress')
        ->assertHasErrors('address.zipcode');
});

it('if not find address in viaCep', function () {

    Livewire::test(Checkout::class)
        ->set('address.zipcode', "59874-000")
        ->call('findAddress')
        ->assertHasErrors('address.zipcode');
});