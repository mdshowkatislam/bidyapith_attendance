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
        Schema::create('db_location_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->tinyInteger('value')->unique();  
            $table->string('db_location')->nullable();
            $table->timestamps();
        
        });
        DB::statement('ALTER TABLE db_location_settings ADD CONSTRAINT chk_key_range CHECK (`value` BETWEEN 1 AND 7)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('db_location_settings');
    }
};
