<?php

namespace App\Livewire;

use App\Mail\MagicLoginMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Login extends Component
{
    use LivewireAlert;
    public string $email = '';


    public function login()
    {
        $this->alert('success', 'Login efetuado com sucesso');

        $user = User::where('email', $this->email)->first();

        if($user){
            $url = URL::temporarySignedRoute(
                name: 'login.store',
                expiration: 3600,
                parameters: ['email' => $user->email],
            );

            Mail::to($user->email)->queue(new MagicLoginMail($url));
            $this->alert('success','Email enviado!');

        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
