<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function limitation()
    {
        return $this->hasOne(Limitations::class);
    }

    public function package()
    {
        return $this->belongsTo(Packages::class, 'projects_id', 'id');
    }
}
