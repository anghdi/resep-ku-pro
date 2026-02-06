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

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate(); // Wajib agar session tidak stuck

            $user = auth()->user();

            // Cek apakah user punya role (Spatie)
            if ($user->roles()->count() === 0) {
                return redirect()->intended(default: '/complete-profile');
            }

            return redirect()->intended('/dashboard');
        }

        session()->flash('error', 'Credentials mismatch.');
    }
    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }

    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'id'])) {
            session()->put('locale', $lang);
        }

        return redirect(request()->header('Referer'));
    }

}
