<?php

namespace App\Models;

use CodeIgniter\Model;

class RecetaModel extends Model
{
    protected $table = 'receta'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    // Campos permitidos para inserciones y actualizaciones
    protected $allowedFields = [
        'nombre',
        'image',
        'detalle',
        'dia',
        'comida',
        'created_at',
        'updated_at'
    ];

    // Habilitar el uso de timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Definir que el campo `id` es autoincrementable
    protected $useAutoIncrement = true;
}
