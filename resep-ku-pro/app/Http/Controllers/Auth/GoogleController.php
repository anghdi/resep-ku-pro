<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();


        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'full_name' => $googleUser->name,
            'email' => $googleUser->email,
            'avatar' => $googleUser->avatar,
            'status' => 'active',
        ]);

        // Login ke sistem Laravel
        Auth::login($user);

        request()->session()->regenerate();

        if (empty($user->role)) {
            return redirect()->route('complete-profile');
        }

        // Lempar ke halaman dashboard setelah sukses
        return redirect()->intended('/dashboard');
    }
}
