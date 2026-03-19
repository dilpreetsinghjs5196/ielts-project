<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
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
