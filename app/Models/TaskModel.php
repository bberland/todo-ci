<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\TaskEntity;

class TaskModel extends Model
{
    protected $table      = 'tasks';
    protected $primaryKey = 'id';

    protected array $casts = [
        'completed' => 'boolean',
    ];

    protected $allowedFields = ['title', 'completed'];

    protected $useTimestamps = true; 
    protected $returnType    = TaskEntity::class;
}
