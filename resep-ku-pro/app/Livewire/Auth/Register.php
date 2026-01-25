<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Register extends Component
{

    public $full_name, $role, $brand_name, $email, $password;

    public function register()
    {
        $this->validate([
            'full_name'  => 'required|min:3',
            'role'       => 'required',
            'brand_name' => 'required|min:2',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
        ]);

        $user = User::create([
            'full_name'  => $this->full_name,
            'role'       => $this->role,
            'org_id'     => $this->brand_name, // Menyimpan Brand ke org_id
            'email'      => $this->email,
            'password'   => Hash::make($this->password),
            'status'     => 'active', // Default status
        ]);

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }

    public function setLocale($lang) {
        session()->put('locale', $lang);
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
