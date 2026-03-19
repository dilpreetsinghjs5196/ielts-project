<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'test_type_id',
        'level_id',
        'test_id',
        'title',
        'passage',
        'audio_file',
        'attachment',
        'instruction',
        'status',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
