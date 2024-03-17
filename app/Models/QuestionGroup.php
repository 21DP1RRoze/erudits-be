<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'disqualify_amount',
        'answer_time',
        'points',
        'is_additional',
        'quiz_id',
    ];

    public function quiz(): BelongsTo {
        return $this->belongsTo(Quiz::class);
    }

    public function questions(): HasMany {
        return $this->hasMany(Question::class);
    }
}
