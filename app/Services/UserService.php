<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{

    public function store(array $userData, array $address)
    {
        $user = User::where('email', $userData['email'])->first(['id', 'name', 'email']);
        if (!$user) {
            $user = User::create([...$userData, 'password' => Hash::make(Str::uuid())]);
        }

        // Validar endereço, para não sair criando vários
        $user->addresses()->create($address);

        return $user;
    }

}