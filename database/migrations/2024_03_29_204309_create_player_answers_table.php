<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("player_id")->constrained()->cascadeOnDelete();
            $table->foreignId("question_id")->constrained()->cascadeOnDelete();
            $table->foreignId("answer_id")->constrained()->cascadeOnDelete();
            $table->timestamp("questioned_at")->nullable(); // This is the time when the question was asked (not when the answer was given
            $table->timestamp("answered_at")->nullable(); // This is the time when the answer was given
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_answers');
    }
};
