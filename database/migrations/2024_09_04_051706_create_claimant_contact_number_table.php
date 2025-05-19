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
        Schema::create('claimant_contact_number', function (Blueprint $table) {
            $table->id();
            $table->string('contact_number');
            $table->unsignedBigInteger('claimant_id');
            $table->timestamps();

            $table->foreign('claimant_id')->references('id')->on('claimant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claimant_contact_number');
    }
};
