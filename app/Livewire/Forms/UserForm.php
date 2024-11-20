<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate('required|email')]
    public $email = "";

    #[Validate(
        [
            'name' => 'required|string|min:3|max:255|regex:/^\s*\S+\s+\S+.*$/'
        ],
        message: [
            'required' => 'O campo :attribute é obrigatório',
            'string' => 'Por favor, insira o :attribute no formato correto.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :min caracteres.',
            'regex' => 'Por favor, insira seu nome completo.',
        ]
    )]
    public $name = "";

    #[Validate('nullable|string|min:3|cpf')]
    public $cpf = "";
}
