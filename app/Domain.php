<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['name'];
    
    protected $attributes = [
        'state' => 'init'
    ];

    public function setAsFailed()
    {
        $this->state = 'failed';
    }

    public function setAsCompleted()
    {
        $this->state = 'completed';
    }
}
