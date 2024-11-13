<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddressForm extends Form
{
    #[Validate('required|string|max:9')]
    public $zipcode = "";

    #[Validate('required|string|max:255')]
    public $address = "";

    #[Validate('required|string|max:255')]
    public $city = "";

    #[Validate('required|string|max:2')]
    public $state = "";

    #[Validate('required|string|max:255')]
    public $district = "";

    #[Validate('required|string|max:255')]
    public $number = "";

    #[Validate('nullable|string|max:255')]
    public $complement = "";

    public function findAddress()
    {
        $zipcode = preg_replace('/[^0-9]/im', '', $this->zipcode);

        if (strlen(trim($zipcode)) < 8) {
            $this->addError('zipcode', 'CEP Inválido');
            return;
        }

        $urlViaCep = "viacep.com.br/ws/{$zipcode}/json/";
        $address = Http::get($urlViaCep)->object();

        if (!$address || (isset($address->erro) && $address->erro)) {
            $this->addError('zipcode', 'CEP Inválido');
            $this->reset(['address', 'city', 'state', 'district', 'number', 'complement']);
            return;
        }

        $this->address = $address->logradouro;
        $this->city = $address->localidade;
        $this->state = $address->uf;
        $this->district = $address->bairro;
        $this->complement = $address->complemento;

    }

}

