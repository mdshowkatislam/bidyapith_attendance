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
        Schema::create('special_working_days', function (Blueprint $table) {
            $table->id();
            $table->date('date');  // special date (e.g., override holiday)
            $table->tinyInteger('day_type')->default(1);  // '0=half working day' ,'1=full working day'
            $table->string('description')->nullable();
             $table->tinyInteger('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_working_days');
    }
};
