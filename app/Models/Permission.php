<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Models\Permission as OriginPermission;

// use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class Permission extends Model
{
    // use HasRoleAndPermission;
    // use PermissionHasRelations;
    // use Slugable;
    use SoftDeletes;
    protected $guarded = [];

}
