<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningPart extends Model
{
    protected $fillable = [
        'listening_test_id',
        'part_number',
        'title',
        'instruction',
        'passage',
        'audio_file',
        'image',
    ];

    public function test()
    {
        return $this->belongsTo(ListeningTest::class, 'listening_test_id');
    }

    public function questions()
    {
        return $this->hasMany(ListeningQuestion::class)->orderByRaw('CAST(question_number AS UNSIGNED), question_number');
    }
}
