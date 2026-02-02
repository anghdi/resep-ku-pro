<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class Register extends Component
{

    public $full_name, $role, $email, $password, $brand_name, $team_org_id;

    protected function rules()
    {
        return [
            'full_name' => 'required|min:3',
            'role'      => 'required|in:owner,manager,staff',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'brand_name'  => $this->role === 'owner' ? 'required|min:3' : 'nullable',
            'team_org_id' => in_array($this->role, ['manager', 'staff']) ? 'required' : 'nullable',
        ];
    }

    public function register()
    {
        // Sekarang cukup panggil satu kali tanpa argumen
        $validatedData = $this->validate();
        // dd($validatedData);

        $user = User::create([
            'full_name' => $this->full_name,
            'email'     => $this->email,
            'password'  => Hash::make($this->password),
            'org_id'    => $this->role === 'owner' ? $this->brand_name : $this->team_org_id,
            'status'    => 'active',
        ]);

        $user->assignRole($this->role);

        session()->flash('success', 'Account successfully created!');
        return redirect()->route('login');
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
