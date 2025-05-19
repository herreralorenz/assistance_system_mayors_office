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
        Schema::create('assistance_type_description', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assistance_type_id');
            $table->unsignedBigInteger('assistance_description_id');
            $table->timestamps();

            $table->foreign('assistance_type_id')->references('id')->on('assistance_type');
            $table->foreign('assistance_description_id')->references('id')->on('assistance_description');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_type_description');
    }
};
