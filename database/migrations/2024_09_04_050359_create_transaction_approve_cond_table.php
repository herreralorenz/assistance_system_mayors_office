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
        Schema::create('transaction_approve_cond', function (Blueprint $table) {
            $table->id();
            $table->string('remarks')->nullable();
            $table->date('status_condition_date');
            $table->unsignedBigInteger('transaction_approve_status_id');
            $table->unsignedBigInteger('transaction_approve_id');
            $table->timestamps();

            $table->foreign('transaction_approve_id')->references('id')->on('transaction_approve');
            $table->foreign('transaction_approve_status_id')->references('id')->on('transaction_approve_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_approve_cond');
    }
};
