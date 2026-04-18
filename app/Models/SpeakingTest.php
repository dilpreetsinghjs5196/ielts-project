<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakingTest extends Model
{
    protected $fillable = [
        'name',
        'test_type_id',
        'level_id',
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
        return $this->hasMany(SpeakingPart::class)->orderBy('part_number');
    }
}
