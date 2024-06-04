<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class LoginPage extends Component{

    public $email;
    public $password;
    
    public function save(){
        $this->validate([
            'email' => 'required|email|max:225|exists:users,email',
            'password' => 'required|min:6|max:225',
        ]);

        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('error', 'Invalid credentials');
            return;
        }

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
