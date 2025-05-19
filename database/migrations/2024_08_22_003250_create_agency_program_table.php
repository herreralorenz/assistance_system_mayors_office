<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     //Example DSWD
    public function up(): void
    {
        Schema::create('agency_program', function (Blueprint $table) {
            $table->id();
            $table->string('agency_program_abbreviation');
            $table->string('agency_program_name');
            $table->unsignedBigInteger('agency_id');
            $table->timestamps();

            $table->foreign('agency_id')->references('id')->on('agency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_program');
    }
};
