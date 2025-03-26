<?php

namespace App\Entities;

class ProductEntity extends BaseEntity{

    protected $attributes = [
        'name' => null,
        'brand' => null,
        'price' => 0.0,
        'active' => true
    ];

    protected $casts = [
        'price' => 'float',
        'active' => 'boolean'
    ];

}

?>