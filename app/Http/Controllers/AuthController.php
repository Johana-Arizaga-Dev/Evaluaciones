<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // Solo usuarios no autenticados pueden acceder al login
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validación
        $request->validate([
            'numero_empleado' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar usuario por número de empleado
        $user = \App\Models\User::where('numero_empleado', $request->numero_empleado)->first();

        // Verificar credenciales manualmente
        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Iniciar sesión manualmente
            Auth::login($user, $request->remember);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Si falla la autenticación
        return back()->withErrors([
            'numero_empleado' => 'Las credenciales proporcionadas no son correctas.',
        ])->withInput($request->only('numero_empleado', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
