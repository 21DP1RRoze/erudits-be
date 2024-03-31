<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function questionGroups(): HasMany {
        return $this->hasMany(QuestionGroup::class);
    }

    public function quizInstances(): HasMany {
        return $this->hasMany(QuizInstance::class);
    }
}
