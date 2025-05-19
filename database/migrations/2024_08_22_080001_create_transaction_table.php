vbdvsdfdfasdfmdfvdfgh<?php

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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id'); // For UUIDs
            $table->unsignedBigInteger('client_id');
            $table->date('date_request');
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('agency_program_id');
            $table->unsignedBigInteger('assistance_type_id');
            $table->unsignedBigInteger('assistance_description_id')->nullable();
            $table->unsignedBigInteger('other_assistance_description_id')->nullable();
            $table->unsignedBigInteger('assistance_category_id');
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->date('due_date')->nullable();
            $table->string('assistance_reason')->nullable();
            $table->timestamps();

            $table->foreign('hospital_id')->references('id')->on('hospital');
            $table->foreign('client_id')->references('id')->on('client');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->foreign('other_assistance_description_id')->references('id')->on('other_assistance_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
