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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('action_id');
            $table->string('model');  // e.g., "Invoice", "User"
            $table->unsignedBigInteger('model_id'); // e.g., ID of the record acted on
            $table->json('changes')->nullable(); // optional: store what changed


            $table->foreign('action_id')->references('id')->on('action_logs');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_models');
    }
};
