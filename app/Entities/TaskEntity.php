<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TaskEntity extends Entity
{
    protected $attributes = [
        'id'        => null,
        'title'     => null,
        'completed' => false,
    ];

    // Getter para 'completed' como booleano
    public function getCompleted()
    {
        return (bool) $this->attributes['completed'];
    }

    // Setter para 'completed'
    public function setCompleted($value)
    {
        $this->attributes['completed'] = (bool) $value;
        return $this;
    }
}
