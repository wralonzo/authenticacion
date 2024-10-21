<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class AuthController extends ResourceController
{
    public function register()
    {
        try {
            // Reglas de validación
            $rules = [
                'role'          => 'required',
                'email'         => 'required',
                'displayName'   => 'required',
                'app'           => 'required',
                'password'      => 'required|min_length[3]'
            ];

            // Validar las reglas
            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }

            // Inicializar el modelo de usuario
            $userModel = new UsersModel();
            $json = $this->request->getJSON();

            // Verificar si el usuario ya existe
            $existingUser = $userModel
                ->where('app', $json->app)
                ->where('email', $json->email)
                ->where('status', 1)
                ->first();

            if ($existingUser) {
                // Si el usuario existe, responder con un mensaje
                return $this->respondCreated([
                    'message' => 'Usuario ya existe',
                    'statusCode' => 201, // Conflict
                    'status' => false
                ]);
            }

            // Preparar los datos para guardar
            $data = [
                'email'         => $json->email,
                'role'          => $json->role,
                'displayName'   => $json->displayName,
                'app'           => $json->app,
                'password'      => password_hash($json->password, PASSWORD_DEFAULT),
            ];

            // Guardar el nuevo usuario
            $userModel->save($data);

            // Responder con éxito
            return $this->respondCreated([
                'message' => 'Usuario registrado correctamente',
                'statusCode' => 201,
                'status' => true
            ]);
        } catch (Exception $e) {
            return $this->failServerError('Internal server error: ' . $e->getMessage());
        }
    }


    public function login()
    {
        try {
            $rules = [
                'email'    => 'required',
                'password' => 'required|min_length[3]',
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }
            $json = $this->request->getJSON();

            $userModel = new UsersModel();
            $user = $userModel
                ->where('app', $json->app)
                ->where('email', $json->email)
                ->where('status', 1)
                ->first();

            if (!$user || !password_verify($json->password, $user['password'])) {
                $response = [
                    'message' => 'Credenciales no válidas intente de nuevo',
                    'logged' => false,
                ];
                return $this->respondCreated($response);
            }

            // Aquí puedes generar un token JWT u otra lógica
            $response = [
                'message' => 'Login successful',
                'logged' => true,
                'user' => $user
            ];

            return $this->respondCreated($response);
        } catch (Exception $e) {
            return $this->failServerError('Internal servcer error: ' . $e->getMessage());
        }
    }

    public function users()
    {
        $userModel = new UsersModel();
        $parametro = $this->request->getGet('app');
        $user = $userModel
            ->where('app', $parametro)
            ->where('status', 1)
            ->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'users' => $user
        ];

        return $this->respond($response);
    }

    public function usersRole()
    {
        $userModel = new UsersModel();
        $parametro = $this->request->getGet('app');
        $parametro2 = $this->request->getGet('role');
        $user = $userModel
            ->where('app', $parametro)
            ->where('role', $parametro2)
            ->where('status', 1)
            ->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'users' => $user
        ];

        return $this->respond($response);
    }

    public function getUser($id)
    {
        try {
            $userModel = new UsersModel();
            $user = $userModel->find($id);
            $response = [
                'message' => 'Login successful',
                'logged' => true,
                'user' => $user
            ];

            return $this->respond($response);
        } catch (Exception $e) {
            return $this->failServerError('Internal servcer error: ' . $e->getMessage());
        }
    }

    public function updateUser($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $userModel = new UsersModel();
            $user = $userModel->find($id);
            if (!$user) {
                return $this->failNotFound('User not found');
            }
            $json = $this->request->getJSON();
            if ($json->password != null) {
                $json->password = password_hash($json->password, PASSWORD_DEFAULT);
            } else {
                unset($json->password);
            }

            $userModel->update($id, $json);
            return $this->respondUpdated(['message' => 'Usuario actualizado', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('Internal servcer error: ' . $e->getMessage());
        }
    }

    public function generatePassword($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $userModel = new UsersModel();
            $user = $userModel
                ->where('status', 1)
                ->find($id);
            if (!$user) {
                return $this->failNotFound('Usuario no encontrado');
            }
            $passwordRamdom = $this->generateRandomPassword();
            $data =  [
                'password' => password_hash($passwordRamdom, PASSWORD_DEFAULT),
            ];

            $userModel->update($id, $data);
            return $this->respondUpdated(['message' => $passwordRamdom, 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('Internal servcer error: ' . $e->getMessage());
        }
    }

    public function forgotPassword()
    {
        try {
            // Validar que el ID del usuario sea válido
            $json = $this->request->getJSON();
            $userModel = new UsersModel();
            $user = $userModel
                ->where('email', $json->email)
                ->where('status', 1)
                ->first();
            if (!$user) {
                return $this->failNotFound('Usuario no encontrado');
            }
            $json->password = password_hash($json->password, PASSWORD_DEFAULT);
            $userModel->update($user['id'], $json);
            return $this->respondUpdated(['message' => 'Contraseña recuperada correctamente', 'statusCode' => 200,]);
        } catch (Exception $e) {
            return $this->failServerError('Error al recuperar la contraseña: ' . $e->getMessage());
        }
    }

    private function generateRandomPassword($length = 10)
    {
        // Definir el conjunto de caracteres para generar la contraseña
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generar una cadena aleatoria de la longitud especificada
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function deleteOne($id)
    {
        try {

            // Buscar el registro en el modelo
            $model = new UsersModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Usuario no encontrado');
            }
            $model->update($id, [
                "status" => 0
            ]);
            // Respuesta exitosa con el registro encontrado
            $response = [
                'message' => 'Usuario eliminado',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200); // Código HTTP 200 OK
        } catch (Exception $e) {
            return $this->failServerError('Internal servcer error: ' . $e->getMessage());
        }
    }
}
