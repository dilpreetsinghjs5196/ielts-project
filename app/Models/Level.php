<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function moduleSets()
    {
        return $this->hasMany(ModuleSet::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
