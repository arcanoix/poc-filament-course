<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = ['nombre', 'curso_id', 'tipo_nivel_id'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function tipoNivel()
    {
        return $this->belongsTo(TipoNivel::class);
    }
}
