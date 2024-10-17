<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsersModel;

class EmailController extends Controller
{

    public function send_email()
    {
        $email = \Config\Services::email();
        $userModel = new UsersModel();
        $users = $userModel->findAll();

        $emailAddresses = [];

        // Extraer los correos de los usuarios
        foreach ($users as $user) {
            $emailAddresses[] = $user['email']; // Asumiendo que el campo de email se llama 'email' en la tabla de usuarios
        }

        // Configurar los detalles del correo
        $email->setFrom('invoicerenergy@gmail.com', '');
        $email->setTo($emailAddresses); 
        $email->setSubject('Automatizacion recetas');
        $email->setMessage('<p>Este es un mensaje de prueba enviado desde CodeIgniter 4 usando Gmail SMTP.</p>');

        // Enviar el correo
        if ($email->send()) {
            echo 'Correo enviado correctamente.';
        } else {
            // Mostrar el error si ocurre algún problema
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
}
