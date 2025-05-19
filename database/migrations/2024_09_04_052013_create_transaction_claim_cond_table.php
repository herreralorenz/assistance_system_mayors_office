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
        Schema::create('transaction_claim_cond', function (Blueprint $table) {
            $table->id();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('transaction_claim_id');
            $table->unsignedBigInteger('transaction_claim_status_id');
            $table->date('status_condition_date');
            $table->timestamps();

            $table->foreign('transaction_claim_id')->references('id')->on('transaction_claim');
            $table->foreign('transaction_claim_status_id')->references('id')->on('transaction_claim_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_claim_cond');
    }
};
