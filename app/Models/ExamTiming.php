<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTiming extends Model
{
    protected $table = 'exam_timings';

    protected $fillable = [
        'exam_time',
    ];
}
