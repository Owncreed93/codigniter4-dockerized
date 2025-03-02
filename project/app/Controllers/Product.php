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
        $products = $this->productService->getAll();
        $data = ['products' => $products];
        return view('/products/index', $data);
    }

    public function create(){
        $data = $this->request->getPost();


        log_message('debug', 'Datos recibidos en create: ' .json_encode($data));
        // print_r($data);

        $response = $this->productService->create($data);

        // PHP Rendering
        // return redirect()->to('/products')->with('message', $response);
        // AJAX RENDERING
        return $this->response->setJSON($response);
    }

    public function edit($id){
        $product = $this->productService->getById($id);
        return view('/products/edit', ['product' => $product]);
    }

    public function update($id){
        // $data = $this->request->getPost();
        $data = $this->request->getRawInput();
        $response = $this->productService->update($id, $data);

        // PHP Rendering
        // return redirect()->to('/products')->with('message', $response);
        // AJAX Rendering
        return $this->response->setJSON($response);
    }

    public function delete($id){
        $response = $this->productService->delete($id);

        // PHP Rendering
        // return redirect()->to('/products')->with('message', $response);
        // AJAX Rendering
        return $this->response->setJSON($response);
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        $products = $this->productService->search($query);

        return view('/products/index', ['products' => $products ]);
    }

    public function list(){
        return $this->response->setJSON($this->productService->getAll());
    }

    public function get($id){
        // $product = $this->response->setJSON($this->productService->getById($id));

        // log_message('debug', 'Producto encontrado: ' . json_encode($product));

        // if (!$product) {
        //     return $this->response->setJSON(['error' => 'Producto doesn\'t exist']);
        // }

        // return $this->response->setJSON($product);
        return $this->response->setJSON($this->productService->getById($id));
    }

}

?>