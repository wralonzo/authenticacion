<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    // Campos permitidos para inserciones y actualizaciones
    protected $allowedFields = [
        'email',
        'password',
        'role',
        'displayName',
        'app',
        'created_at',
        'updated_at',
    ];

    // Habilitar el uso de timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
