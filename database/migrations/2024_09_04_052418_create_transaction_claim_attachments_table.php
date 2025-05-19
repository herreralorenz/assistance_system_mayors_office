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
        Schema::create('transaction_claim_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachments');
            $table->unsignedBigInteger('transaction_claim_id');
            $table->timestamps();

            $table->foreign('transaction_claim_id')->references('id')->on('transaction_claim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_claim_attachments');
    }
};
