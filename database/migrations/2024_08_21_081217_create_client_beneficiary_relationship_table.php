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
        Schema::create('client_beneficiary_relationship', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->unsignedBigInteger('relationship_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('client');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiary');
            $table->foreign('relationship_id')->references('id')->on('relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_beneficiary_relationship');
    }
};
