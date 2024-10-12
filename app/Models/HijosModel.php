<?php

namespace App\Models;

use CodeIgniter\Model;

class HijosModel extends Model
{
    protected $table = 'hijos'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    // Campos permitidos para inserciones y actualizaciones
    protected $allowedFields = [
        'nombres',
        'padre',
        'peso',
        'talla',
        'altura',
        'edad',
        'created_at',
        'updated_at'
    ];

    // Habilitar el uso de timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
