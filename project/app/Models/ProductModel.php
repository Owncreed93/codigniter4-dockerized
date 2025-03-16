<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ProductEntity;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';
    protected $returnType = ProductEntity::class;

    protected $allowedFields = [ 'name', 'brand', 'price', 'active' ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected function initialize()
    {
        $this->where('active', true);
    }

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'brand' => 'required|min_length[3]',
        'price' => 'required|greater_than[0]'
    ];

}

?>