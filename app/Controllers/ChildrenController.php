<?php

namespace App\Controllers;

use App\Models\HijosModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class ChildrenController extends ResourceController
{

    public function display()
    {
        $model = new HijosModel();
        $userModel = new UsersModel();
        $id = $this->request->getGet('id');
        $user = $userModel->first(intval($id));
        $data = [];

        if (!$user) {
            $data = $model->findAll();
        } else {
            if ($user['role'] == 'Admin') {
                $data = $model->findAll();
            } else {
                $data = $model->where('padre', $user['id'])->findAll();
            }
        }
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'data' => $data
        ];

        return $this->respond($response);
    }


    public function register()
    {
        try {
            $model = new HijosModel();;
            $response = $model->save($this->request->getJSON());
            return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 201]);
        } catch (Exception $e) {
            return $this->respondCreated(['message' => $e]);
        }
    }

    public function upgrade($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $model = new HijosModel();
            $record = $model->find($id);
            if (!$record) {
                return $this->failNotFound('record not found');
            }
            $response = $model->where('id', $id)
                ->set($this->request->getJSON())
                ->update();
            return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            $model = new HijosModel();
            $record = $model->find($id);
            if (!$record) {
                return $this->failNotFound('Record not found');
            }
            $response = [
                'message' => 'Record found successfully',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $model = new HijosModel();
            $record = $model->find($id);
            if (!$record) {
                return $this->failNotFound('Record not found');
            }
            $model->delete($id);
            $response = [
                'message' => 'Registro eliminado correcramente',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200); // Código HTTP 200 OK
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }
}
