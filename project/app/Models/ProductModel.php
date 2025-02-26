<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';

    protected $allowedFields = [ 'name', 'brand', 'price' ];

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'brand' => 'required|min_length[3]',
        'price' => 'required|greater_than[0]'
    ];
}

?>