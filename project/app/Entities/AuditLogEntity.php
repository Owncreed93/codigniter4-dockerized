<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AuditLogEntity extends Entity
{
    protected $attributes = [
        'user_id' => null,
        'model' => null,
        'record_id' => null,
        'action' => null,
        'old_data' => null,
        'new_data' => null,
        'logged_at' => null
    ];

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if ( isset($data['model']) && is_object($data['model']) ){
            $this->attributes['model'] = $this->extractModelName($data['model']);
        }
    }

    public function extractModelName($model): string{
        return (new \ReflectionClass($model))->getShortName();
    }
}
