<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table      = 'tasks';
    protected $primaryKey = 'id';

    protected $allowedFields = ['title', 'completed'];

    protected $useTimestamps = false; // usamos created_at manual
    protected $returnType    = 'array';
}
