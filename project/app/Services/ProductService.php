<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Validations\ProductValidator;

class ProductService{

    protected $productModel;
    protected $logger;

    public function __construct()
    {
        $this->productModel= new ProductModel();
        $this->logger = service('logger');
    }

    public function getAll(){

        try {
            return $this->productModel->findAll();
        } catch (\Throwable $th) {
           $this->logger->error('Error whil fetching the product:', $th->getMessage());
           return ['errors' => 'No se pudieron obtener los productos.'];
        }
        
    }

    public function getById($id){

        try {
            return $this->productModel->find($id);
        } catch (\Throwable $th) {
            $this->logger->error('Error while fetching the product ID {$id}: ' .$th->getMessage());
            return ['errors' => 'No se pudo encontrar el producto.'];
        }
        
    }

    public function create(array $data){

        try {
            $validation = ProductValidator::validate($data);

            if(!empty($validation['errors'])){
                return [ 'errors' => $validation['errors'] ];
            }

            if (!$this->productModel->insert($validation['data'])) {
            // if (!$this->productModel->insert($data)) {
                return ['errors' => $this->productModel->errors()];
            }

            $this->logger->info('Product created'. json_encode($validation['data']));
            return [
                'success' => true,
                'message' => 'Producto creado correctamente.'
            ];
        } catch (\Throwable $th) {
            $this->logger->error('Error while creating product: ' .$th->getMessage());
            return ['errors' => 'Ocurrió un error al crear el producto.'];
        }

    }

    public function update($id, array $data){

        try {
            $validation = ProductValidator::validate($data);

            if(!empty($validation['errors'])){
                return [ 'errors' => $validation['errors'] ];
            }

            if (!$this->productModel->update($id, $validation['data'])){
                return [ 'errors' => $this->productModel->errors()];
            }

            $this->logger->info('Product ID {$id}');
            return [ 
                'success' => true,
                'message' => 'Producto actualizado correctamente.'
            ];
        } catch (\Throwable $th) {
            $this->logger->error('Error updating the product with ID {$id}' .$th->getMessage());
            return ['errors' => 'Ocurrió un error al actualizar el producto.'];
        }
        
    }

    public function delete($id){
        try {
            if (!$this->productModel->delete($id)){
                return ['error' => 'No se pudo eliminar el producto.'];
            }

            $this->logger->info('Product ID {$id} deleted');
            return ['success' => 'Producto eliminado correctamente.'];
        } catch (\Throwable $th) {
            $this->logger->error('Error while deleting the product: ' .$th->getMessage());
            return ['errors' => 'Ocurrió un error al eliminar el producto.'];
        }
        
    }

    public function search($query){
        try {

            $this->logger->info('Product: searching for... {$query}');
            return $this->productModel
                    ->like('name', $query)
                    ->orLike('brand', $query)
                    ->findAll();
        } catch (\Throwable $th) {
            $this->logger->error('Error while searching using the parameters: ' .$th->getMessage());
            return ['errors'=> 'Ocurrió un error al buscar por los parámetros.'];
        }
        
    }

}

?>