<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class Register extends Component
{

    public $email, $password, $password_confirmation;

    public function register()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => null,   // Masih kosong
            'org_id' => null, // Belum punya organisasi
        ]);

        // Kirim email verifikasi (logic-nya sudah ada di Laravel, tinggal setting SMTP nanti)
        // $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect()->route('complete-profile');
    }

    public function setLocale($lang) {
        session()->put('locale', $lang);
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
