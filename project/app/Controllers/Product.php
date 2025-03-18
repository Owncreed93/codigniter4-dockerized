<?php

namespace App\Controllers;

use App\Services\ProductService;


class Product extends BaseController
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index(){
        $styles = ['assets/css/products.css'];
        $scripts = ['assets/js/products.js'];
        $products = $this->productService->getAll();
        $data = [
            'title' => 'Products',
            'products' => $products,
            'styles' => $styles,
            'scripts' => $scripts
        ];
        return view('/products/index', $data);
    }

    public function create(){

        try {
            $data = $this->request->getPost();

            log_message('debug', 'Data recieved on _create: ' .json_encode($data));

            $response = $this->productService->create($data);

            if (isset($response['errors'])) {
                log_message('error', 'Error while creating ' .json_encode($response['errors']));
                return $this->response
                        ->setStatusCode(422)
                        ->setJSON(['status' => 'error', 'errors'=> $response['errors'] ]);
            }

            // PHP Rendering
            // return redirect()->to('/products')->with('message', $response);
            // AJAX RENDERING
            return $this->response
                ->setStatusCode(201)
                ->setJSON([ 'status'=> $response['success'], 'message'=> $response['message'] ]);
        } catch (\Throwable $th) {
            log_message('error', 'Error en product creation: ' .$th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors'=> 'Error.' ]);
        }
        
    }

    public function edit($id){
        $product = $this->productService->getById($id);
        return view('/products/edit', ['product' => $product]);
    }

    public function update($id){

        try {
            // $data = $this->request->getPost();
            $data = $this->request->getRawInput();

            log_message('debug', 'Data recieved on _edit: ' .json_encode($data));

            $response = $this->productService->update($id, $data);

            if (isset($response['errors'])) {
                log_message('error', 'Error while modifing product: ' .json_encode($response['errors']));
                return $this->response
                        ->setStatusCode(422)
                        ->setJSON(['status' => 'error', 'errors'=> $response['errors'] ]);
            }

            // PHP Rendering
            // return redirect()->to('/products')->with('message', $response);
            // AJAX Rendering
            return $this->response
                ->setStatusCode(200)
                // ->setJSON([ 'status'=> $response['success'], 'message'=> $response ]);
                ->setJSON([ 'status'=> $response['success'], 'message'=> $response['message'] ]);
        } catch (\Throwable $th) {
            log_message('error', 'Error en product modification: ' .$th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors'=> 'Error.' ]);
        }
        
    }

    public function delete($id){
        try {

            log_message('debug', 'Data recieved on _delete: ' .json_encode($id));
            $response = $this->productService->delete($id);

            // PHP Rendering
            // return redirect()->to('/products')->with('message', $response);
            // AJAX Rendering
            return $this->response->setJSON($response);
        } catch (\Throwable $th) {
            log_message('error', 'Error in product deletion: ' .$th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors'=> 'Error.' ]);
        }
        
    }

    public function softDelete($id)
    {
        try {
            log_message('debug', "Soft deleting product with ID: " .$id);
            $response = $this->productService->softDelete($id);

            if (isset($response['errors'])) {
                log_message('error', "Error while soft deleting product: " . json_encode($response['errors']));
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['status' => 'error', 'errors' => $response['errors']]);
            }

            return $this->response
                ->setStatusCode(200)
                ->setJSON(['status' => $response['success'], 'message' => $response['message']]);
        } catch (\Throwable $th) {
            log_message('error', "Error in soft deleting product: " . $th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors' => 'Error al desactivar el producto.']);
        }
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        $products = $this->productService->search($query);

        return view('/products/index', ['products' => $products ]);
    }

    public function list(){
        try {
            log_message('debug', 'Fetching product\'s data...');
            return $this->response->setJSON($this->productService->getAll());
        } catch (\Throwable $th) {
            log_message('error', 'Error in product\'s fetching list: ' .$th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors'=> 'Error.' ]);
        }
        
    }

    public function get($id){
        try {
            // $product = $this->response->setJSON($this->productService->getById($id));

            // log_message('debug', 'Producto encontrado: ' . json_encode($product));

            // if (!$product) {
            //     return $this->response->setJSON(['error' => 'Producto doesn\'t exist']);
            // }

            // return $this->response->setJSON($product);
            log_message('debug', 'Fetching product\'s data with id: ' .$id);

            return $this->response->setJSON($this->productService->getById($id));
        } catch (\Throwable $th) {
            log_message('error', 'Error in product\'s feching: ' .$th->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'errors'=> 'Error.' ]);
        }
        
    }

}

?>