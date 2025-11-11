<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    public function index()
    {
        $puestos = Puesto::withCount('usuarios')
            ->orderBy('nivel_jerarquico')
            ->orderBy('nombre_puesto')
            ->get();

        // Agrupar puestos por nivel de jerarquía para la vista
        $puestosPorNivel = $puestos->groupBy('nivel_jerarquico');

        return view('puestos.index', compact('puestos', 'puestosPorNivel'));
    }

    public function create()
    {
        // Mostrar todos los niveles del 1 al 9 disponibles
        $nivelesJerarquia = range(1, 9);

        return view('puestos.create', compact('nivelesJerarquia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_puesto' => 'required|string|max:255|unique:puestos',
            'nivel_jerarquico' => 'required|integer|between:1,9',
        ]);

        Puesto::create([
            'nombre_puesto' => $request->nombre_puesto,
            'nivel_jerarquico' => $request->nivel_jerarquico,
        ]);

        return redirect()->route('puestos.index')
            ->with('success', 'Puesto creado exitosamente.');
    }

    public function show(Puesto $puesto)
    {
        $puesto->load(['usuarios' => function($query) {
            $query->with('area');
        }]);

        // Obtener otros puestos con el mismo nivel
        $puestosMismoNivel = Puesto::where('nivel_jerarquico', $puesto->nivel_jerarquico)
            ->where('id', '!=', $puesto->id)
            ->get();

        return view('puestos.show', compact('puesto', 'puestosMismoNivel'));
    }

    public function edit(Puesto $puesto)
    {
        // Mostrar todos los niveles del 1 al 9 (ya no restringimos por niveles disponibles)
        $nivelesJerarquia = range(1, 9);

        return view('puestos.edit', compact('puesto', 'nivelesJerarquia'));
    }

    public function update(Request $request, Puesto $puesto)
    {
        $request->validate([
            'nombre_puesto' => 'required|string|max:255|unique:puestos,nombre_puesto,' . $puesto->id,
            'nivel_jerarquico' => 'required|integer|between:1,9',
        ]);

        $puesto->update([
            'nombre_puesto' => $request->nombre_puesto,
            'nivel_jerarquico' => $request->nivel_jerarquico,
        ]);

        return redirect()->route('puestos.index')
            ->with('success', 'Puesto actualizado exitosamente.');
    }

    public function destroy(Puesto $puesto)
    {
        if ($puesto->usuarios()->exists()) {
            return redirect()->route('puestos.index')
                ->with('error', 'No se puede eliminar el puesto porque tiene usuarios asignados.');
        }

        $puesto->delete();
        return redirect()->route('puestos.index')
            ->with('success', 'Puesto eliminado exitosamente.');
    }

    // Método para ver puestos por nivel
    public function porNivel($nivel)
    {
        $puestos = Puesto::withCount('usuarios')
            ->where('nivel_jerarquico', $nivel)
            ->orderBy('nombre_puesto')
            ->get();

        return view('puestos.por-nivel', compact('puestos', 'nivel'));
    }
}
