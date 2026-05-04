<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningTest extends Model
{
    protected $fillable = [
        'name',
        'test_type_id',
        'level_id',
        'audio_file',
        'status',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function parts()
    {
        return $this->hasMany(ListeningPart::class)->orderBy('part_number');
    }

    public function attempts()
    {
        return $this->hasMany(ListeningAttempt::class);
    }
}
