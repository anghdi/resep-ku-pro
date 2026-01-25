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
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();


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

        // Lempar ke halaman dashboard setelah sukses
        return redirect()->intended('/dashboard');
    }
}
