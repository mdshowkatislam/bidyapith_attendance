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
       Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid')->unique();
            $table->foreignId('division_id')
                  ->nullable()
                  ->constrained('divisions')
                  ->nullOnDelete();
            $table->string('district_name_en')->nullable();
            $table->string('district_name_bn');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
