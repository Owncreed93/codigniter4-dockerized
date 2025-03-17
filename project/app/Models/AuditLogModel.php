<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\AuditLogEntity;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $returnType       = AuditLogEntity::class;
    protected $allowedFields    = [
        'user_id',
        'model',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'logged_at'
    ];

    protected $validationRules = [
        'user_id' => 'required',
        'model' => 'required',
        'record_id' => 'required',
        'action' => 'required',
        'old_data' => 'required',
        'new_data' => 'required',
        'logged_at' => 'required'
    ];
}
