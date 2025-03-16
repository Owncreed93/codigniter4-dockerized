<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BaseEntity extends Entity {

    protected $attributes = ['active' => true];

    protected $casts = ['active' => 'boolean'];

    public function toggleActive()
    {
        $this->attributes['active'] = !$this->attributes['active'];
        return $this;
    }

}

?>