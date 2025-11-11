<?php

namespace App\Http\Controllers;

use App\Models\TipoPregunta;
use Illuminate\Http\Request;

class TipoPreguntaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->puedeAdministrar()) {
                abort(403, 'No tienes permisos para gestionar tipos de pregunta.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $tiposPregunta = TipoPregunta::withCount('preguntas')->get();
        return view('tipo-preguntas.index', compact('tiposPregunta'));
    }

    public function create()
    {
        return view('tipo-preguntas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:255|unique:tipo_preguntas',
            'descripcion' => 'nullable|string|max:500',
        ]);

        TipoPregunta::create([
            'nombre_tipo' => $request->nombre_tipo,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('tipo-preguntas.index')
            ->with('success', 'Tipo de pregunta creado exitosamente.');
    }

    public function edit(TipoPregunta $tipoPregunta)
    {
        return view('tipo-preguntas.edit', compact('tipoPregunta'));
    }

    public function update(Request $request, TipoPregunta $tipoPregunta)
    {
        $request->validate([
            'nombre_tipo' => 'required|string|max:255|unique:tipo_preguntas,nombre_tipo,' . $tipoPregunta->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $tipoPregunta->update([
            'nombre_tipo' => $request->nombre_tipo,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('tipo-preguntas.index')
            ->with('success', 'Tipo de pregunta actualizado exitosamente.');
    }

    public function destroy(TipoPregunta $tipoPregunta)
    {
        if ($tipoPregunta->preguntas()->exists()) {
            return redirect()->route('tipo-preguntas.index')
                ->with('error', 'No se puede eliminar el tipo de pregunta porque tiene preguntas asociadas.');
        }

        $tipoPregunta->delete();
        return redirect()->route('tipo-preguntas.index')
            ->with('success', 'Tipo de pregunta eliminado exitosamente.');
    }
}
