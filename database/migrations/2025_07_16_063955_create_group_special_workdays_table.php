<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.special_workingday_id
     */
    public function up(): void
    {
        Schema::create('group_special_workdays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('special_workingday_id');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('special_workingday_id')->references('id')->on('special_workdays')->onDelete('cascade');
            $table->unique(['group_id', 'special_workingday_id']);  // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('group_special_workdays');
    }
};