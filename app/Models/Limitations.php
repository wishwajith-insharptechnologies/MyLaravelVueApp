<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Limitations extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

    public function package()
    {
        return $this->belongsTo(Packages::class);
    }
}
