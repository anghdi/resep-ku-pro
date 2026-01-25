<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->intended('/dashboard');
        }

        session()->flash('error', __('Invalid credentials.'));
    }
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'id'])) {
            session()->put('locale', $lang);
        }

        return redirect(request()->header('Referer'));
    }

}
