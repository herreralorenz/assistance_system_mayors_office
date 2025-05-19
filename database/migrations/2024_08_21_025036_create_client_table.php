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
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->unsignedBigInteger('suffix_id')->nullable();
            $table->date('birthdate');
            $table->integer('age');
            $table->unsignedBigInteger('sex_id');
            $table->unsignedBigInteger('civil_status_id');
            $table->string('street')->nullable();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('precint_id')->nullable();


            $table->timestamps();


            $table->foreign('civil_status_id')->references('id')->on('civil_status');
            $table->foreign('sex_id')->references('id')->on('sex');
            $table->foreign('suffix_id')->references('id')->on('suffix');
            $table->foreign('precint_id')->references('id')->on('precint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
