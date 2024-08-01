<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = ['nombre', 'descripcion', 'nivel_id'];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }
}
