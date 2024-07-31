<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function limitation()
    {
        return $this->hasOne(Limitations::class);
    }

    public function project()
    {
        return $this->hasOne(Projects::class, 'id', 'projects_id');
    }

}
