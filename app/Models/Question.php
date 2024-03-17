<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'image',
        'group_id',
        'correct_answer_id',
    ];

    public function group(): BelongsTo {
        return $this->belongsTo(Group::class);
    }
    public function answer(): BelongsTo {
        return $this->belongsTo(Answer::class);
    }
}
