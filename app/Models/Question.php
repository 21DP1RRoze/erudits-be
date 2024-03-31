<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'image',
        'is_open_answer',
        'question_group_id',
    ];

    public function question_group(): BelongsTo {
        return $this->belongsTo(QuestionGroup::class);
    }

    public function answers(): HasMany {
        return $this->hasMany(Answer::class);
    }

    public function open_answers(): HasMany {
        return $this->hasMany(OpenAnswer::class);
    }

    public function player_answers(): HasMany {
        return $this->hasMany(PlayerAnswer::class);
    }
}
