<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Solo usuarios no autenticados pueden acceder al registro
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $puestos = Puesto::orderBy('nivel_jerarquico')->get();
        return view('auth.register', compact('puestos'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre_empleado' => 'required|string|max:255',
            'apellidos_empleado' => 'required|string|max:255',
            'numero_empleado' => 'required|string|unique:users',
            'puesto_id' => 'required|exists:puestos,id',
            'fecha_ingreso' => 'required|date',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'nombre_empleado' => $request->nombre_empleado,
                'apellidos_empleado' => $request->apellidos_empleado,
                'numero_empleado' => $request->numero_empleado,
                'puesto_id' => $request->puesto_id,
                'fecha_ingreso' => $request->fecha_ingreso,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            return redirect('/dashboard')->with('success', '¡Registro exitoso! Bienvenido al sistema.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al crear el usuario: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
