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
        Schema::create('branches', function (Blueprint $table) {
         $table->id();
            $table->bigInteger('uid')->unique()->nullable();
            $table->bigInteger('branch_id')->nullable(); 
            $table->string('branch_name_en')->nullable();
            $table->string('branch_name_bn')->nullable();
            $table->string('branch_location')->nullable();
            $table->bigInteger('head_of_branch_id')->nullable();
            $table->bigInteger('eiin')->nullable();
            $table->tinyInteger('rec_status')->default(1);
            $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
