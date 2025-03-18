<?php

namespace App\Services;

use App\Entities\AuditLogEntity;
use App\Models\ProductModel;
use App\Models\AuditLogModel;
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
           $this->logger->error('Error whil fetching the product:' .$th->getMessage());
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

            $id = $this->productModel->getInsertID();

            $this->logger->info('Product created: '. json_encode($validation['data']));
            /**
             *? IS IT A GOOD IDEA TO CREATE METHODS FOR AUDITLOGENTITY?
             *? CRUD WILL HAVE PROPERTY: ACTION AFFECTED WITH create/update/delete
             *? ENUM NEEDED FOR ACTION PROPERTY?
             */
            $auditLog = new AuditLogEntity([
                'user_id' => 0, // auth()->id() ?? null
                'model' => $this->productModel,
                'record_id' => $id,
                'action' => 'create',
                'old_data' => 'Newly added', //TODO: VERIFY WHY THE "ACTIVE" FIELD DOESN'T APPEAR ON THE BD RECORD
                'new_data' => json_encode($validation['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'logged_at' => date('Y-m-d H:i:s')
            ]);

            $auditLogModel = new AuditLogModel();
            $auditLogModel->insert($auditLog);
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

            $oldData = $this->productModel->find($id);

            $validation = ProductValidator::validate($data);

            if(!empty($validation['errors'])){
                return [ 'errors' => $validation['errors'] ];
            }

            if (!$this->productModel->update($id, $validation['data'])){
                return [ 'errors' => $this->productModel->errors()];
            }

            $this->logger->info('Product ID {$id}');

            $auditLog = new AuditLogEntity([
                'user_id' => 0,
                'model' => $this->productModel,
                'record_id' => $id,
                'action' => 'update',
                'old_data' => $oldData->toJson(),
                'new_data' => json_encode($validation['data'], JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES),
                'logged_at' => date('Y-m-d H:i:s')   
            ]);

            $auditLogModel = new AuditLogModel();
            $auditLogModel->insert($auditLog);

            return [ 
                'success' => true,
                'message' => 'Producto actualizado correctamente.'
            ];
        } catch (\Throwable $th) {
            $this->logger->error('Error updating the product with ID ' .$id .': ' .$th->getMessage());
            return ['errors' => 'Ocurrió un error al actualizar el producto.'];
        }
        
    }

    public function delete($id){
        try {

            // Obtener el producto
            $product = $this->productModel->find($id);

            if (!$product) {
                return ['errors' => 'Producto no encontrado.'];
            }

            if (!$this->productModel->delete($id)){
                return ['error' => 'No se pudo eliminar el producto.'];
            }

            $auditLog = new AuditLogEntity([
                'user_id'   => 0, // auth()->id() ?? null (Se actualizará cuando se implemente autenticación)
                'model'     => $this->productModel,
                'record_id' => $id,
                'action'    => 'delete',
                'old_data'  => $product->toJson(), // Estado anterior
                'new_data'  => 'deleted', // Nuevo estado
                'logged_at' => date('Y-m-d H:i:s')
            ]);

            $auditLogModel = new AuditLogModel();
            $auditLogModel->insert($auditLog);

            $this->logger->info('Product\'s id: ' .$id .' deleted.');
            return ['success' => 'Producto eliminado correctamente.'];
        } catch (\Throwable $th) {
            $this->logger->error('Error while deleting the product: ' .$th->getMessage());
            return ['errors' => 'Ocurrió un error al eliminar el producto.'];
        }
        
    }

    public function softDelete($id)
    {
        try {
            // Obtener el producto
            $product = $this->productModel->find($id);

            if (!$product) {
                return ['errors' => 'Producto no encontrado.'];
            }

            // Aplicar el método toggleActive() desde BaseEntity
            $product->toggleActive();

            // Guardar los cambios en la base de datos
            if (!$this->productModel->update($id, ['active' => $product->active])) {
                return ['errors' => 'No se pudo desactivar el estado del producto.'];
            }

            // Registrar en AuditLog
            $auditLog = new AuditLogEntity([
                'user_id'   => 0, // auth()->id() ?? null (Se actualizará cuando se implemente autenticación)
                'model'     => $this->productModel,
                'record_id' => $id,
                'action'    => 'soft_delete',
                'old_data'  => json_encode(['active' => !$product->active]), // Estado anterior
                'new_data'  => json_encode(['active' => $product->active]), // Nuevo estado
                'logged_at' => date('Y-m-d H:i:s')
            ]);

            $auditLogModel = new AuditLogModel();
            $auditLogModel->insert($auditLog);

            // Registrar en el logger
            $this->logger->info("Product ID {$id} soft deleted.");

            return ['success' => true, 'message' => 'Producto desactivado correctamente.'];
        } catch (\Throwable $th) {
            $this->logger->error('Error in soft deleting product: ' . $th->getMessage());
            return ['errors' => 'Ocurrió un error al desactivar el producto.'];
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