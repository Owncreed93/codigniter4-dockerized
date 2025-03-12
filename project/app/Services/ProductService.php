<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Validations\ProductValidator;

class ProductService{

    protected $productModel;

    public function __construct()
    {
        $this->productModel= new ProductModel();
    }

    public function getAll(){
        return $this->productModel->findAll();
    }

    public function getById($id){
        return $this->productModel->find($id);
    }

    public function create(array $data){


        $validation = ProductValidator::validate($data);

        if(!empty($validation['errors'])){
            return [ 'errors' => $validation['errors'] ];
        }

        if (!$this->productModel->insert($data)) {
            return ['errors' => $this->productModel->errors()];
        }

        return [
            'success' => true,
            'message' => 'Producto creado correctamente.'
        ];

    }

    public function update($id, array $data){

        $validation = ProductValidator::validate($data);

        if(!empty($validation['errors'])){
            return [ 'errors' => $validation['errors'] ];
        }

        if (!$this->productModel->update($id, $data)){
            return [ 'errors' => $this->productModel->errors()];
        }

        return [ 
            'success' => true,
            'message' => 'Producto actualizado correctamente.'
        ];
    }

    public function delete($id){
        if (!$this->productModel->delete($id)){
            return ['error' => 'No se pudo eliminar el producto.'];
        }
        return ['success' => 'Producto eliminado correctamente.'];
    }

    public function search($query){
        return $this->productModel
                    ->like('name', $query)
                    ->orLike('brand', $query)
                    ->findAll();
    }

}

?>