<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'is_active',
        'id_slug',
        'quiz_id',
        'active_question_group_id',
        'active_question_group_start',
    ];

    protected $casts = [
        'active_question_group_start' => 'datetime',
    ];

    public function quiz(): BelongsTo {
        return $this->belongsTo(Quiz::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function activeQuestionGroup(): BelongsTo
    {
        return $this->belongsTo(QuestionGroup::class, 'active_question_group_id');
    }
}
