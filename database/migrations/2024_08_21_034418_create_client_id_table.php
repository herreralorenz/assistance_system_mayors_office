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
        Schema::create('client_id', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('id_number')->nullable();
            $table->unsignedBigInteger('id_type_id')->nullable();
            $table->unsignedBigInteger('other_id_type_id')->nullable();


            $table->foreign('client_id')->references('id')->on('client');
            $table->foreign('id_type_id')->references('id')->on('id_type');
            $table->foreign('other_id_type_id')->references('id')->on('other_id_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_id');
    }
};
