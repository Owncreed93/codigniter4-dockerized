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
        $response = $this->productService->create($data);

        return redirect()->to('/products')->with('message', $response);
    }

    public function edit($id){
        $product = $this->productService->getById($id);
        return view('/products/edit', ['product' => $product]);
    }

    public function update($id){
        $data = $this->request->getPost();
        $response = $this->productService->update($id, $data);

        return redirect()->to('/products')->with('message', $response);
    }

    public function delete($id){
        $response = $this->productService->delete($id);
        return redirect()->to('/products')->with('message', $response);
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        $products = $this->productService->search($query);

        return view('/products/index', ['products' => $products ]);
    }

}

?>