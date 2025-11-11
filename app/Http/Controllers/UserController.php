<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Solo usuarios con nivel 1 pueden acceder a estas rutas
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->puedeAdministrar()) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::with(['puesto'])
            ->orderBy('nombre_empleado')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $puestos = Puesto::orderBy('nivel_jerarquico')->get();

        return view('users.create', compact('puestos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_empleado' => 'required|string|max:255',
            'apellidos_empleado' => 'required|string|max:255',
            'numero_empleado' => 'required|string|unique:users',
            'puesto_id' => 'required|exists:puestos,id',
            'fecha_ingreso' => 'required|date',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'nombre_empleado' => $request->nombre_empleado,
            'apellidos_empleado' => $request->apellidos_empleado,
            'numero_empleado' => $request->numero_empleado,
            'puesto_id' => $request->puesto_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user)
    {
        $user->load(['puesto',  'evaluacionesComoEvaluado', 'evaluacionesComoEvaluador']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $puestos = Puesto::orderBy('nivel_jerarquico')->get();

        return view('users.edit', compact('user', 'puestos'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre_empleado' => 'required|string|max:255',
            'apellidos_empleado' => 'required|string|max:255',
            'numero_empleado' => [
                'required',
                'string',
                Rule::unique('users')->ignore($user->id),
            ],
            'puesto_id' => 'required|exists:puestos,id',
            'fecha_ingreso' => 'required|date',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'nombre_empleado' => $request->nombre_empleado,
            'apellidos_empleado' => $request->apellidos_empleado,
            'numero_empleado' => $request->numero_empleado,
            'puesto_id' => $request->puesto_id,
            'fecha_ingreso' => $request->fecha_ingreso,
        ];

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        // Prevenir que un usuario se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Verificar si el usuario tiene evaluaciones relacionadas
        if ($user->evaluacionesComoEvaluador()->exists() || $user->evaluacionesComoEvaluado()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'No se puede eliminar el usuario porque tiene evaluaciones relacionadas.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
