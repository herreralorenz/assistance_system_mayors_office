<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     //Example FINANCIAL ASSISTANCE
    public function up(): void
    {
        Schema::create('assistance_type', function (Blueprint $table) {
            $table->id();
            $table->string('assistance_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_type');
    }
};
