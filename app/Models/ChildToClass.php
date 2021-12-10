<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildToClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'child_to_class';

    public function class()
    {
        return $this->hasOne('App\Models\Clazz', 'id', 'class_id');
    }
}
