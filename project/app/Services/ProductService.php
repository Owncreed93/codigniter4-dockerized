<?php

namespace App\Services;

use App\Models\ProductModel;

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

        if (!$this->productModel->insert($data)) {
            return ['error' => $this->productModel->errors()];
        }
        return ['success' => 'Producto creado correctamente.'];
    }

    public function update($id, array $data){
        if (!$this->productModel->update($id, $data)){
            return [ 'error' => $this->productModel->errors()];
        }
        return [ 'success' => 'Producto actualizado correctamente.'];
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