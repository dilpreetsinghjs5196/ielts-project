<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'writing_test_id', 
        'task_number', 
        'title', 
        'instruction', 
        'question_text', 
        'image', 
        'sample_answer', 
        'marks'
    ];

    public function writingTest()
    {
        return $this->belongsTo(WritingTest::class);
    }
}
