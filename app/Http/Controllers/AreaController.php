<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->puedeAdministrar()) {
                abort(403, 'No tienes permisos para gestionar áreas.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $areas = Area::withCount('formularios')->get();
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_area' => 'required|string|max:255|unique:areas'
        ]);

        Area::create([
            'nombre_area' => $request->nombre_area
        ]);

        return redirect()->route('areas.index')
            ->with('success', 'Área creada exitosamente.');
    }

    public function show(Area $area)
    {
        $area->load(['formularios' => function($query) {
            $query->with(['creador', 'preguntas']);
        }]);

        return view('areas.show', compact('area'));
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nombre_area' => 'required|string|max:255|unique:areas,nombre_area,' . $area->id

        ]);

        $area->update([
            'nombre_area' => $request->nombre_area
        ]);

        return redirect()->route('areas.index')
            ->with('success', 'Área actualizada exitosamente.');
    }

    public function destroy(Area $area)
    {
        // Verificar si el área tiene formularios asignados
        if ($area->formularios()->exists()) {
            return redirect()->route('areas.index')
                ->with('error', 'No se puede eliminar el área porque tiene formularios asignados.');
        }

        $area->delete();
        return redirect()->route('areas.index')
            ->with('success', 'Área eliminada exitosamente.');
    }
}
