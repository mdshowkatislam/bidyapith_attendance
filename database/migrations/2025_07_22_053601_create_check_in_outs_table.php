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
        Schema::create('check_in_outs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');  //FK relation,
            $table->integer('log_id')->nullable();
            $table->string('machine_id')->nullable(); 
            $table->date('date')->nullable();  // ✅ '2025-05-21'
            $table->string('in_time')->nullable();  // ✅ '02:34 PM' requires string
            $table->string('out_time')->nullable(); 
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('profile_id')->on('employees')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_outs');
    }
};
