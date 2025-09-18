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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('shift_id');
            $table->tinyInteger('status')->default(1);
            $table->integer('flexible_in_time')->nullable();
            $table->integer('flexible_out_time')->nullable();
            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('shift_settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
