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
        'question_group_id',
    ];

    public function group(): BelongsTo {
        return $this->belongsTo(QuestionGroup::class);
    }
    public function answers(): HasMany {
        return $this->hasMany(Answer::class);
    }
}
