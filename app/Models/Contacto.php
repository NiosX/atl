<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'email'];

    protected $with = ['telefonos'];

    public function telefonos()
    {
        return $this->hasMany(TelefonoContacto::class);
    }
}
