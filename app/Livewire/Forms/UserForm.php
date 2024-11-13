<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate('required|email')]
    public $email = "";

    #[Validate('required|string|min:3|max:255')]
    public $name = "";

    #[Validate('nullable|string|min:3|cpf')]
    public $cpf = "";
}
