<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingTest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'test_type_id', 'level_id', 'status'];

    public function tasks()
    {
        return $this->hasMany(WritingTask::class);
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
