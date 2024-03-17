<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'question_id',
    ];

    public function question(): BelongsTo {
        return $this->belongsTo(Question::class);
    }
}
