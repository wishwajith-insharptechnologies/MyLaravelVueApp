<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Models\Role as OriginRole;

class Role extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
