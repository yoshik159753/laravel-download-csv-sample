<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Parent は使えないので ParentModel とする。
 */
class ParentModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parents';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
