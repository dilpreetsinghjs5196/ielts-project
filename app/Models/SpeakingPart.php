<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakingPart extends Model
{
    protected $fillable = [
        'speaking_test_id',
        'part_number',
        'title',
        'instruction',
        'passage',
    ];

    public function test()
    {
        return $this->belongsTo(SpeakingTest::class, 'speaking_test_id');
    }

    public function questions()
    {
        return $this->hasMany(SpeakingQuestion::class);
    }
}
