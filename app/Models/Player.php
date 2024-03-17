<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points',
        'is_disqualified',
        'instance_id',
    ];

    public function quiz_instance(): BelongsTo {
        return $this->belongsTo(QuizInstance::class);
    }
}
