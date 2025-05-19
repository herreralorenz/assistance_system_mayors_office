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
        Schema::create('sent_sms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_approve_id');
            $table->unsignedBigInteger('message_id');
            $table->json('response')->nullable();
            $table->json('webhook')->nullable();
            $table->longtext('http_sms_id')->nullable();
            $table->timestamps();

            $table->foreign('transaction_approve_id')->references('id')->on('transaction_approve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_sms');
    }
};
