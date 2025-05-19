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
        Schema::create('beneficiary_occupation', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('beneficiary_id');
            $table->unsignedBigInteger('occupation_id');
            $table->double('monthly_income')->nullable();
            $table->timestamps();

            $table->foreign('occupation_id')->references('id')->on('occupation');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_occupation');
    }
};
