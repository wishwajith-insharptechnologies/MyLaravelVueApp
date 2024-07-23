<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Contracts\Role as RoleContract;


class Role extends Model
{
    // use SoftDeletes;
    protected $guarded = [];
}
