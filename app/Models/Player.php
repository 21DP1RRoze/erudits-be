<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points',
        'is_disqualified',
        'quiz_instance_id',
    ];

    public function quiz_instance(): BelongsTo {
        return $this->belongsTo(QuizInstance::class);
    }
}
