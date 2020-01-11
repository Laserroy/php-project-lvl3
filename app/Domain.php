<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SM\Factory\Factory as SMFactory;

class Domain extends Model
{
    protected $fillable = ['name'];
    
    protected $attributes = [
        'state' => 'initiated'
    ];

    public function stateMachine()
    {
        $factory = new SMFactory(config('state-machine'));
        return $factory->get($this, 'domain_graph');
    }
}
