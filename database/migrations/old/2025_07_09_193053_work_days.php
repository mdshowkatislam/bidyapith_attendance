<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_days', function (Blueprint $table) {
             $table->id();
             $table->string('day_name')->unique(); // e.g. Monday, Tuesday, etc.
              $table->boolean('is_weekend')->default(false);
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('work_days');
    }
};
