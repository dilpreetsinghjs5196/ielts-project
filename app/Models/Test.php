<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name',
        'module_set_id',
        'level_id',
        'category_id',
        'test_type_id',
        'status',
    ];

    public function moduleSet()
    {
        return $this->belongsTo(ModuleSet::class);
    }

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

    public function questionGroups()
    {
        return $this->hasMany(QuestionGroup::class);
    }

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class);
    }
}
