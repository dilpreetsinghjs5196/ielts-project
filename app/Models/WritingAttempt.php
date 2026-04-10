<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WritingAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'writing_test_id',
        'answers',
        'feedback',
        'score',
        'status',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function writingTest()
    {
        return $this->belongsTo(WritingTest::class, 'writing_test_id');
    }
}
