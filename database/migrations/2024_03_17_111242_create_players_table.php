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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("points")->default(0);
            $table->integer("tiebreaker_points")->default(0);
            $table->boolean("is_active")->default(false);
            $table->boolean("is_disqualified")->default(false);
            $table->boolean("is_tiebreaking")->default(false);

            $table->foreignId("quiz_instance_id")->constrained("quiz_instances")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
