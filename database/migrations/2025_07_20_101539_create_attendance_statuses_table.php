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
           Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();       // e.g. Present, Absent, Late In
            $table->string('short_form', 5)->unique(); // e.g. P, A, LI (short form max length 5 chars)
            $table->tinyInteger('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_statuses');
    }
};
