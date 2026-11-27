<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /* ===================== WEB ===================== */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password'], 'is_active' => true],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            // Token API untuk halaman admin (dipakai DataTables lewat AJAX)
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->where('name', 'web_admin_token')->delete();
            $token = $user->createToken('web_admin_token')->plainTextToken;
            session(['api_token' => $token]);

            return redirect()->intended(route('admin.home'));
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah, atau akun tidak aktif.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $user->tokens()->where('name', 'web_admin_token')->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda sudah logout.');
    }

    /* ===================== API ===================== */

    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $base = Str::lower(Str::before($validatedData['email'], '@'));
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $username,
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User berhasil didaftarkan',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function loginApi(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Akun tidak aktif',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logoutApi(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }
}