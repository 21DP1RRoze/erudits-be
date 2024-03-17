<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'is_active',
        'id_slug',
        'quiz_id'
    ];

    public function quiz(): BelongsTo {
        return $this->belongsTo(Quiz::class);
    }
}
