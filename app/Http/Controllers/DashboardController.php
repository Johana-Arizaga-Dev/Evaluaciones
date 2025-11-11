<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\User;
use App\Models\Formulario;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Estadísticas básicas
        $estadisticas = [
            'evaluaciones_realizadas' => Evaluacion::where('evaluador_id', $user->id)->count(),
            'evaluaciones_recibidas' => Evaluacion::where('evaluado_id', $user->id)->count(),
            'promedio_evaluaciones' => Evaluacion::where('evaluado_id', $user->id)->avg('porcentaje_obtenido') ?? 0,
        ];

        // Evaluaciones recientes
        $evaluacionesRecientes = Evaluacion::with(['formulario', 'evaluado'])
            ->where('evaluador_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Usuarios pendientes por evaluar
        $usuariosPendientes = $user->getUsuariosEvaluables()->count();

        return view('dashboard', compact('estadisticas', 'evaluacionesRecientes', 'usuariosPendientes'));
    }

    public function adminDashboard()
    {
        if (!auth()->user()->puedeAdministrar()) {
            abort(403, 'No tienes permisos de administración.');
        }

        $estadisticas = [
            'total_usuarios' => User::count(),
            'total_evaluaciones' => Evaluacion::count(),
            'total_formularios' => Formulario::count(),
            'promedio_general' => Evaluacion::avg('porcentaje_obtenido') ?? 0,
        ];

        return view('admin.dashboard', compact('estadisticas'));
    }
}
