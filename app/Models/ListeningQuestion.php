<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningQuestion extends Model
{
    protected $fillable = [
        'listening_part_id',
        'question_number',
        'question_type',
        'title',
        'common_heading',
        'content',
        'options',
        'correct_answer',
        'explanation',
        'image',
        'images',
        'marks',
        'settings',
    ];

    protected $casts = [
        'options' => 'array',
        'images' => 'array',
        'settings' => 'array',
    ];

    public function part()
    {
        return $this->belongsTo(ListeningPart::class, 'listening_part_id');
    }
}
