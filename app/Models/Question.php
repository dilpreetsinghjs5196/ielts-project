<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_group_id',
        'category_id',
        'test_type_id',
        'level_id',
        'question_type',
        'audio_file',
        'passage',
        'attachment',
        'title',
        'content',
        'options',
        'correct_answer',
        'explanation',
        'marks',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(QuestionGroup::class, 'question_group_id');
    }

    protected $casts = [
        'options' => 'array',
    ];

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
}
