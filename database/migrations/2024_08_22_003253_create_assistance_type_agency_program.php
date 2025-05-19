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
        Schema::create('assistance_type_agency_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_program_id');
            $table->unsignedBigInteger('assistance_type_id');
            $table->timestamps();

            $table->foreign('agency_program_id')->references('id')->on('agency_program');
            $table->foreign('assistance_type_id')->references('id')->on('assistance_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_type_agency_program');
    }
};
