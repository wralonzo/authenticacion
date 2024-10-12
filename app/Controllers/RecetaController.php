<?php

namespace App\Controllers;

use App\Models\RecetaModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class RecetaController extends ResourceController
{

    public function display()
    {
        $userModel = new RecetaModel();
        $data = $userModel->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'data' => $data
        ];

        return $this->respond($response);
    }

    public function dia()
    {
        $model = new RecetaModel();
        $id = $this->request->getGet('id');
        $data = [];
        if (!$id) {
            $data = $model->findAll();
        } else {
            $data = $model->where('dia', $id)->findAll();
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
            $model = new RecetaModel();
            $file = $this->request->getFile('image');
            $nombreArchivo = '';
            if ($file->isValid()) {
                $nombreArchivo = $file->getRandomName();
                $rutaDestino = '../public/uploads/';
                if (!is_dir($rutaDestino)) {
                    mkdir($rutaDestino, 0777, true);
                }
                if ($file->move($rutaDestino, $nombreArchivo)) {
                    $saveData = [
                        'nombre' => $this->request->getVar('nombre'),
                        'detalle' => $this->request->getVar('detalle'),
                        'dia' => $this->request->getVar('dia'),
                        'comida'        => $this->request->getVar('comida'),
                        'image' => $nombreArchivo,
                    ];
                    $response = $model->save($saveData);
                    return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 201]);
                } else {
                    return $this->respondCreated(['message' => 'No se guardo el archivo', 'statusCode' => 201]);
                }
            }
            return $this->respondCreated(['message' =>  $this->request->getFile('image'), 'statusCode' => 201]);
        } catch (Exception $e) {
            return $this->respondCreated(['message' => $e]);
        }
    }

    public function upgrade($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $model = new RecetaModel();
            $record = $model->find($id);
            if (!$record) {
                return $this->failNotFound('record not found');
            }
            $file = $this->request->getFile('image');

            $nombreArchivo = '';
            if ($file != null && $file->isValid()) {
                $nombreArchivo = $file->getRandomName();
                $rutaDestino = '../public/uploads/';
                if (!is_dir($rutaDestino)) {
                    mkdir($rutaDestino, 0777, true);
                }
                $file->move($rutaDestino, $nombreArchivo);
            } else {
                $nombreArchivo = $record['image'];
            }

            $dataUpdate = [
                'nombre'        => $this->request->getVar('nombre'),
                'detalle'       => $this->request->getVar('detalle'),
                'dia'           => $this->request->getVar('dia'),
                'image'         => $nombreArchivo,
                'comida'        => $this->request->getVar('comida'),
            ];
            $response = $model->where('id', $id)
                ->set($dataUpdate)
                ->update();
            return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {

            // Buscar el registro en el modelo
            $model = new RecetaModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Record not found');
            }

            // Respuesta exitosa con el registro encontrado
            $response = [
                'message' => 'Record found successfully',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200); // Código HTTP 200 OK
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {

            // Buscar el registro en el modelo
            $model = new RecetaModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Record not found');
            }
            $model->delete($id);
            // Respuesta exitosa con el registro encontrado
            $response = [
                'message' => 'Record found successfully',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200); // Código HTTP 200 OK
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }
}
