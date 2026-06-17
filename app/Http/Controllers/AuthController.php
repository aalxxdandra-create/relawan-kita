<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller {

    public function showLogin() {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        if (Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole()->with('success', 'Selamat datang, '.Auth::user()->name.'!');
        }

        return back()->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    public function showRegister() {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.register');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required','email','unique:users,email','regex:/^[^@]+@(gmail\.com|relawankita\.com|developer\.com)$/i'],
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'email.regex'        => 'Email harus menggunakan domain @gmail.com untuk user, @relawankita.com untuk admin, atau @developer.com untuk developer.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $domain = Str::lower(Str::after($validated['email'], '@'));
        $role = match ($domain) {
            'developer.com' => 'developer',
            'relawankita.com' => 'admin',
            default => 'user',
        };

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang, '.$user->name.'!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda berhasil logout.');
    }

    private function redirectByRole() {
        if (Auth::user()->isDeveloper()) {
            return redirect()->route('developer.dashboard');
        }

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.events.index');
        }

        return redirect()->route('home');
    }
}