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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();  
            $table->bigInteger('eiin')->nullable();     //foreign key institutes   
            $table->bigInteger('caid')->unique()->nullable();
            $table->bigInteger('uid')->unique();
            $table->bigInteger('pdsid')->unique()->nullable(); 
            $table->string('index_number')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('incremental_no')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_bn')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('institute_type')->nullable();
            $table->string('institute_category')->nullable();
            $table->string('workstation_name')->nullable();
            $table->string('branch_institute_name')->nullable();
            $table->string('branch_institute_category')->nullable();            
            $table->string('service_break_institute')->nullable();
            $table->string('designation')->nullable();
            $table->integer('designation_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('upazilla_id')->nullable();
            $table->tinyInteger('is_foreign')->default('0')->comment('1=foreign,0=local');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->integer('joining_year')->nullable();
            $table->integer('mpo_code')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('last_working_date')->nullable();
            $table->string('nid')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('image')->nullable();
            $table->string('role', 30)->nullable();
            $table->tinyInteger('ismpo')->nullable();
            $table->tinyInteger('isactive')->nullable();
            $table->tinyInteger('data_source')->nullable();
            $table->string('teacher_source')->nullable();
            $table->integer('teacher_type')->nullable();
            $table->integer('access_type')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
