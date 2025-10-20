<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
        }

        $stored = (string) $user->password;
        $isBcrypt = Str::startsWith($stored, ['$2y$', '$2b$', '$2a$']);

        if ($isBcrypt) {
            // Standard bcrypt password
            if (Hash::check($request->password, $stored)) {
                Auth::login($user);
                $user->last_login = now();
                $user->save();
                return redirect()->route('dashboard');
            }
        } else {
            // Legacy SHA1 password check
            if (sha1($request->password) === $stored) {
                // Upgrade to bcrypt automatically
                $user->password = Hash::make($request->password);
                $user->last_login = now();
                $user->save();

                Auth::login($user);
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
