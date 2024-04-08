<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points',
        'tiebreaker_points',
        'is_disqualified',
        'is_tiebreaking',
        'quiz_instance_id',
    ];

    public function quiz_instance(): BelongsTo {
        return $this->belongsTo(QuizInstance::class);
    }

    public function open_answers(): HasMany {
        return $this->hasMany(OpenAnswer::class);
    }

    public function player_answers(): HasMany {
        return $this->hasMany(PlayerAnswer::class);
    }
}
