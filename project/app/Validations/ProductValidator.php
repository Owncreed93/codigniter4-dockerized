<?php

namespace App\Validations;

class ProductValidator{
    /**
     * Sanitize and validate products before processing them.
     */

    public static function validate(array $data){
        helper('text');

        $errors = [];

        // Sanitize
        $data['name'] = esc(strip_tags($data['name']));
        $data['brand'] = esc(strip_tags($data['brand']));
        $data['price'] = esc(filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

        if(empty($data['name'])) {
            $errors['name'] = 'El nombre del producto es obligatorio.';
        }

        if(empty($data['brand'])){
            $errors['brand'] = 'El nombre de la marca es obligatorio.';
        }

        if( !is_numeric($data['price']) || floatval($data['price']) <= 0){
            $errors['price'] = 'El precio debe ser un número mayor a 0.';
        }

        return [ 'data'=> $data, 'errors' => $errors ];
    }
}

?>