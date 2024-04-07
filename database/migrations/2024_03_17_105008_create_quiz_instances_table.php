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
        Schema::create('quiz_instances', function (Blueprint $table) {
            $table->id();
            $table->boolean("is_public")->default(true);
            $table->boolean("is_active")->default(true);
            $table->string("id_slug")->nullable();
            $table->foreignId("quiz_id")->constrained()->cascadeOnDelete();
            $table->foreignId("active_question_group_id")->nullable()->constrained("questions")->cascadeOnDelete();
            $table->timestamp("active_question_group_start")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_instances');
    }
};
