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
        Schema::dropIfExists('employees'); // ✅ This drops the table if it exists
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('eiin')->nullable();      
            $table->bigInteger('caid')->unique()->nullable();
             $table->integer('profile_id')->unique()->comment('emp_id');; // this is the emp_id
             $table->tinyInteger('person_type')
             ->comment('1 = Teacher, 2 = Staff, 3 = Student');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
