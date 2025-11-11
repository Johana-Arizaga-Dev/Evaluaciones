<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre_empleado',
        'apellidos_empleado',
        'numero_empleado',
        'puesto_id',
        'fecha_ingreso',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'password' => 'hashed',
    ];

    // Relaciones
    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }

    public function formulariosCreados()
    {
        return $this->hasMany(Formulario::class, 'creado_por');
    }

    public function evaluacionesComoEvaluador()
    {
        return $this->hasMany(Evaluacion::class, 'evaluador_id');
    }

    public function evaluacionesComoEvaluado()
    {
        return $this->hasMany(Evaluacion::class, 'evaluado_id');
    }

    // Métodos de verificación basados en número de jerarquía
    public function puedeCrearFormularios()
    {
        // Niveles 1-5 pueden crear formularios
        return $this->puesto && $this->puesto->nivel_jerarquico <= 5;
    }

    public function puedeEvaluar()
    {
        // Todos pueden evaluar excepto el nivel 9 (el más bajo)
        return $this->puesto && $this->puesto->nivel_jerarquico < 9;
    }

    public function puedeAdministrar()
    {
        // Solo nivel 1 puede administrar
        return $this->puesto && $this->puesto->nivel_jerarquico == 1;
    }

    // Método para obtener usuarios evaluables
    public function getUsuariosEvaluables()
    {
        if (!$this->puesto || !$this->puedeEvaluar()) {
            return collect();
        }

        return User::whereHas('puesto', function($query) {
            $query->where('nivel_jerarquico', '>', $this->puesto->nivel_jerarquico);
        })->with('puesto')->get();
    }

    // Verificar si puede evaluar a un usuario específico
    public function puedeEvaluarUsuario(User $evaluado)
    {
        if (!$this->puesto || !$evaluado->puesto) {
            return false;
        }

        return $this->puesto->nivel_jerarquico < $evaluado->puesto->nivel_jerarquico;
    }

    // Obtener el nivel de jerarquía
    public function getNivelJerarquicoAttribute()
    {
        return $this->puesto ? $this->puesto->nivel_jerarquico : null;
    }

    // Para autenticación con número de empleado - QUITAR ESTO
    // public function getAuthIdentifierName()
    // {
    //     return 'numero_empleado';
    // }

    // En su lugar, usar el método login personalizado en AuthController
}
