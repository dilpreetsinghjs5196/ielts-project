<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'listening_test_id',
        'answers',
        'score',
        'status',
        'time_left',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function listeningTest()
    {
        return $this->belongsTo(ListeningTest::class, 'listening_test_id');
    }
}
