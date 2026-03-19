<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSet extends Model
{
    protected $fillable = [
        'name',
        'level_id',
        'category_id',
        'test_type_id',
        'status',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
