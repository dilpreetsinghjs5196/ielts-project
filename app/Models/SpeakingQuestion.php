<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakingQuestion extends Model
{
    protected $fillable = [
        'speaking_part_id',
        'question_text',
        'audio_file',
    ];

    public function part()
    {
        return $this->belongsTo(SpeakingPart::class, 'speaking_part_id');
    }
}
